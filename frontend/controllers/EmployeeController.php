<?php

namespace frontend\controllers;

use common\models\City;
use common\models\User;
use common\models\UserLocation;
use common\models\Shop;
use frontend\models\employee\UserOffice;
use frontend\models\employee\UserShop;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;


/**
 * Class EmployeeController
 * @package frontend\controllers
 *
 */
class EmployeeController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('registration', [
                'actionForm' => 'Place',
                'model' => ''
            ]
        );
    }

    public function actionPlace()
    {
        $place = Yii::$app->request->post('placeOfWork');
        if ($place == 'shop') {
            $actionForm = 'Shop';
        } else {
            $actionForm = 'Office';
        }
        $modelName = 'frontend\\models\\employee\\User' . $actionForm;
        return $this->render('registration', [
            'actionForm' => $actionForm,
            'model' => new $modelName()
        ]);
    }

    public function actionUserShop()
    {
        $newUser = Yii::$app->request->post('UserShop');
        if ($newUser && $this->saveUser($newUser)) {
            return $this->goHome();
        }
    }

    public function actionShops($term = null, $id = null)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (empty($term) && empty($id)) {
            return ['results' => []];
        }

        $shops = Shop::find();
        if (!empty($id)) {
            $shops->withCity($id);
       }

        if (!empty($term)) {
            $shops->withTerm($term);
        }

        $listShops = $shops->orderBy('id')->all();

        $results = [];
        foreach ($listShops as $shop) {
            $results[] = ['id' => $shop->id, 'text' => $shop->number];
        }
        return ['results' => $results];
    }

    public function actionUserOffice()
    {
        $newUser = Yii::$app->request->post('UserOffice');

        if ($newUser && $this->saveUser($newUser)) {
            return $this->goHome();
        }
    }

    /**
     * @param array $newUser
     * @return bool
     * @throws \Exception
     * @throws \yii\db\Exception
     */
    private function saveUser($newUser)
    {
        $transaction = User::getDb()->beginTransaction();
        try {
            $user = new User();
            $user->username = $newUser['login'];
            $user->email = $newUser['email'];
            $user->password = $newUser['password'];
            $arFio = array($newUser['name'], $newUser['surname'], User::FIO_SUFFIX);
            $user->fio = implode(" ", $arFio);

            if ($newUser['activity'] == 'shopModerator') {
                $user->role = $user::ROLE_SHOP_MODERATOR;
                $user->shop_id = $newUser["shop"];
            } elseif ($newUser['activity'] == 'shop') {
                $user->role = $user::ROLE_SHOP;
                $user->shop_id = $newUser["shop"];
            } elseif ($newUser['activity'] == 'marketing' || ($newUser['activity'] == 'other' && $newUser['activity_other'] == 'marketing'))
                $user->role = $user::ROLE_MARKETING;
            elseif ($newUser['activity'] == 'purchase')
                $user->role = $user::ROLE_PURCHASE;
            elseif ($newUser['activity'] == 'other' && $newUser['activity_other'] == 'viewJournalOnlyAllCities')
                $user->role = $user::ROLE_VIEW_JOURNAL_ONLY_ALL_CITIES;
           elseif ($newUser['activity'] == 'other' && $newUser['activity_other'] == 'marketing_plus')
                $user->role = $user::ROLE_MARKETING_PLUS;

            $user->generateAuthKey();

            if ($user->save()) {
                if ($user->role == $user::ROLE_SHOP_MODERATOR || $user->role == $user::ROLE_SHOP) {
                    /**@var City $city */
                    $city = City::findOne($newUser['city']);
                    $user->link('cities', $city);

                    $userLocation = new UserLocation();
                    $userLocation->user_id = $user->id;
                    $userLocation->city_id = $city->id;
                    $userLocation->save();
                }
                $user->confirmation();
            }
            $transaction->commit();
            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }
}

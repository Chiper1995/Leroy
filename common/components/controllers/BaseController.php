<?php
namespace common\components\controllers;

use common\models\User;
use Yii;
use yii\helpers\Url;
use yii\web\Controller;

class BaseController extends Controller
{
    public $noAccessMessage = 'У вас нет доступа для выполнения этой операции';

    public function beforeAction($action)
    {
        $this->updateLastVisit();

        $returnUrl = Yii::$app->request->get('returnUrl', null);

        // Убираем инфу о pjax, для сохранения в returnUrl
        if ($returnUrl != null) {
            $returnUrl = strtr(preg_replace('%(\_pjax\=\%23[^&]*)%', '', $returnUrl), ['&&'=>'&', '?&'=>'?']);
            // Уберем ?, если он последний
            $returnUrl = preg_replace('%(\?|\&)$%', '', $returnUrl);
            // ANDR: Без этого не работает на хостинге, проверить почему
            $_GET['returnUrl'] = $returnUrl;
        }

        Url::remember($returnUrl);

        return parent::beforeAction($action);
    }


    //обновляем дату последнего посещения юзера при любом заходе на сайт
    private function updateLastVisit()
    {
        if (!empty($user = \Yii::$app->user->identity)) {
            /** @var $user User */
            $user->last_visit = (new \Datetime())->getTimestamp();
            $user->visit_notified = 0;
            $user->save();
        }
    }

}

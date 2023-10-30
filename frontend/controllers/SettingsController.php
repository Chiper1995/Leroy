<?php
namespace frontend\controllers;

use common\components\actions\ListAction;
use common\components\controllers\BaseController;
use common\components\controllers\IModelController;
use common\models\settings\Settings;
use common\models\settings\SettingsRewards;
use common\rbac\Rights;
use frontend\models\SettingsSearch;
use ReflectionClass;
use Yii;
use yii\base\Model;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class SettingsController extends BaseController implements IModelController
{

    public function getModelClass()
    {
        return Settings::className();
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'index' => [
                'class' => ListAction::className(),
                'searchModelClass' => SettingsSearch::className(),
                'access' => Rights::SHOW_SETTINGS,
            ],
        ];
    }


    /***
     * Редактирование
     * @param $id
     * @return array|string|Response
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        if (Yii::$app->request->post('cancel') !== null) {
            return $this->redirect(['settings/index']);
        }

        // Проверка доступа
        if (!Yii::$app->user->can(Rights::SHOW_SETTINGS))
            throw new ForbiddenHttpException($this->noAccessMessage);

        /**@var Settings $settingsModel*/
        $settingsModel = (new ReflectionClass($this->getModelClass()))->newInstance();
        if (($settingsModel = $settingsModel::findOne($id)) === null) {
            throw new NotFoundHttpException("{$this->getModelClass()} not found");
        }
        $settingsModel->setScenario('update');

        /**@var Model $model*/
        $model = (new ReflectionClass('common\\models\\settings\\' .$settingsModel->name))->newInstance();
        $model->setScenario('update');
        if ($settingsModel->value != '')
            $model->attributes = unserialize($settingsModel->value);


        // Если ajax
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if (isset($_POST[$settingsModel->name])) {
            $model->attributes = $_POST[$settingsModel->name];
            if ($model->validate()) {
                $settingsModel->value = serialize($model->attributes);

                if ($model instanceof SettingsRewards) {
                    $model->saveJournalTypesRewards(Yii::$app->request->post('JournalType', []));
                }

                if ($settingsModel->save(false)) {
                    return $this->redirect(['settings/index']);
                }
                else {
                    Yii::$app->session->setFlash('error', Html::icon('alert') . ' Возникла ошибка при сохранении');
                }
            }
        }

        return $this->render('update', ['settingsModel'=>$settingsModel, 'model'=>$model,]);
    }

}
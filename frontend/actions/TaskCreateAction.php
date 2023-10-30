<?php
namespace frontend\actions;

use common\components\actions\ModelAction;
use common\models\Task;
use common\models\TaskPhoto;
use ReflectionClass;
use Yii;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Response;

class TaskCreateAction extends TaskAction
{
    /**
     * @var string view for action
     */
    public $view = 'create';

    /**
     * @return array|string|Response
     */
    public function run()
    {
        if (Yii::$app->request->post('cancel') !== null) {
            return $this->controller->goBack(Url::toRoute([$this->returnUrl]));
        }

        /**@var Task $model*/
        $model = (new ReflectionClass($this->getModelClass()))->newInstance();

        $model->setScenario($this->modelScenario);

        // Проверка доступа
        $this->checkAccess($model);

        // Если ajax
        if (!Yii::$app->request->isPjax && Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($this->processPostData($model, !Yii::$app->request->isPjax)) {
                if (!Yii::$app->request->isPjax) {
                    if ($this->savePostData($model)) {
                        return $this->controller->goBack(Url::toRoute([$this->returnUrl]));
                    } else {
                        Yii::$app->session->setFlash('error', Html::icon('alert') . ' Возникла ошибка при сохранении');
                    }
                }
            } else {
                Yii::$app->session->setFlash('error', Html::icon('alert') . ' Проверьте правильность заполнения полей');
            }
        }

        return $this->controller->render($this->view, [
            'model' => $model,
        ]);
    }
}
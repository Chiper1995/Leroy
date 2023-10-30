<?php
namespace common\components\actions;

use Yii;
use ReflectionClass;
use common\components\ActiveRecord;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class UpdateAction extends ModelAction
{
    /**
     * @var string view for action
     */
    public $view = 'update';

    /**
     * @var string scenario for models
     */
    public $modelScenario = 'update';

    /**
     * @var string|array url for return after success update
     */
    public $returnUrl = 'index';

    /**
     * @param $id
     * @return array|string|Response
     * @throws NotFoundHttpException
     * @throws \ErrorException
     */
    public function run($id)
    {
        if (Yii::$app->request->post('cancel') !== null) {
            return $this->controller->goBack(Url::toRoute([$this->returnUrl]));
        }

        /**@var ActiveRecord $model*/
        $model = (new ReflectionClass($this->getModelClass()))->newInstance();
        if (($model = $model::findOne($id)) === null) {
            throw new NotFoundHttpException("{$this->getModelClass()} not found");
        }

        $model->setScenario($this->modelScenario);

        // Проверка доступа
        $this->checkAccess($model);

        // Если ajax
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                return $this->controller->goBack(Url::toRoute([$this->returnUrl]));
            }
            else {
                //Yii::$app->response->setStatusCode(400); //HttpResponse::STATUS_UNVALIDATE_DATA
            }
        }

        return $this->controller->render($this->view, [
            'model' => $model,
        ]);
    }
}
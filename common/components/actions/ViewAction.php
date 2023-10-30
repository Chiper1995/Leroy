<?php
namespace common\components\actions;

use Yii;
use ReflectionClass;
use common\components\ActiveRecord;
use yii\bootstrap\ActiveForm;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class ViewAction extends ModelAction
{
    /**
     * @var string view for action
     */
    public $view = 'view';

    /**
     * @var string scenario for models
     */
    public $modelScenario = 'view';

    /**
     * @var string|array url for return after success update
     */
    public $returnUrl = 'index';

    /**
     * @param $id
     * @return array|string|Response
     * @throws NotFoundHttpException
     */
    public function run($id)
    {
        if (Yii::$app->request->post('cancel') !== null) {
            return $this->controller->redirect([$this->returnUrl]);
        }

        /**@var ActiveRecord $model*/
        $model = (new ReflectionClass($this->getModelClass()))->newInstance();
        if (($model = $model::findOne($id)) === null) {
            throw new NotFoundHttpException("{$this->getModelClass()} not found");
        }

        $model->setScenario($this->modelScenario);

        // Проверка доступа
        $this->checkAccess($model);

        return $this->controller->render($this->view, [
            'model' => $model,
        ]);
    }
}
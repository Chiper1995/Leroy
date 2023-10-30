<?php
namespace common\components\actions;

use ReflectionClass;
use Yii;
use common\components\ActiveRecord;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

class DeleteAction extends ModelAction
{
    /**
     * @var string scenario for models
     */
    public $modelScenario = 'delete';

    /**
     * @var string|array url for return after success update
     */
    public $returnUrl = 'index';

    /**
     * @param $id mixed
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws \ErrorException
     * @throws \Exception
     */
    public function run($id)
    {
        /**@var ActiveRecord $model*/
        $model = (new ReflectionClass($this->getModelClass()))->newInstance();
        if (($model = $model::findOne($id)) === null) {
            throw new NotFoundHttpException("{$this->getModelClass()} not found");
        }

        $model->setScenario($this->modelScenario);

        // Проверка доступа
        $this->checkAccess($model);

        $model->delete();

        return $this->controller->goBack(Url::toRoute([$this->returnUrl]));
    }
}
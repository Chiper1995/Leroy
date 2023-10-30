<?php
namespace frontend\actions;

use ReflectionClass;
use Yii;
use common\components\ActiveRecord;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use common\components\actions\ModelAction;
use common\events\AppEvents;
use common\events\custom\TriggerCuratorEvent as Event;
use common\models\User;

class UserDeleteAction extends ModelAction
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
        if (($model = User::findOne($id)) === null) {
            throw new NotFoundHttpException("{$this->getModelClass()} not found");
        }

        $model->setScenario($this->modelScenario);

        // Проверка доступа
        $this->checkAccess($model);

        // Вызываем событие
        \Yii::$app->trigger(AppEvents::EVENT_USER_DELETE, new Event([
            'sender' => $model,
            'curatorID' => \Yii::$app->user->identity->id,
        ]));

        $model->delete();

        return $this->controller->goBack(Url::toRoute([$this->returnUrl]));
    }
}

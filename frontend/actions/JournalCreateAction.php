<?php
namespace frontend\actions;

use common\models\Journal;
use ReflectionClass;
use Yii;
use yii\helpers\Url;
use yii\web\Response;

class JournalCreateAction extends JournalAction
{
    public $modelScenario = 'create';

    /**
     * @return array|string|Response
     */
    public function run()
    {
        if (Yii::$app->request->post('cancel') !== null) {
            return $this->controller->goBack(Url::toRoute([$this->returnUrl]));
        }

        /**@var Journal $model*/
        $model = (new ReflectionClass($this->getModelClass()))->newInstance();

        $model->setScenario($this->modelScenario);

        // Проверка доступа
        $this->checkAccess($model);

        // Сохраняем и переходим в редактирование
        $model->user_id = Yii::$app->user->id;
        $model->status = Journal::STATUS_DRAFT;
        $model->subject = 'Новая запись от '.date('d.m.Y');
        if ($model->save()) {
            return $this->controller->redirect(Url::toRoute(['update', 'id' => $model->id]));
        }
        else {
            print_r($model->errors); die();
            return $this->controller->goBack(Url::toRoute([$this->returnUrl]));
        }
    }
}
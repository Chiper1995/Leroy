<?php
namespace frontend\actions;

use common\models\Journal;
use common\models\JournalType;
use common\models\Task;
use common\models\TaskUser;
use ReflectionClass;
use Yii;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class JournalCreateFromTaskAction extends JournalAction
{
    public $modelScenario = 'create';

    /**
     * @param integer $id
     * @return array|string|Response
     * @throws NotFoundHttpException
     */
    public function run($id)
    {
        if (Yii::$app->request->post('cancel') !== null) {
            return $this->controller->goBack(Url::toRoute([$this->returnUrl]));
        }

        /**@var Task $task*/
        if (($task = Task::findOne($id)) === null) {
            throw new NotFoundHttpException("Task not found");
        }

        // Если запись уже есть
        $j =  $task->getFamilies()->andWhere(['user_id'=>Yii::$app->user->id])->one()->journal;
        if ($j != null)
            return $this->controller->redirect(['journal/update', ['id'=>$j->id]]);

        /**@var Journal $model*/
        $model = (new ReflectionClass($this->getModelClass()))->newInstance();
        $model->setScenario($this->modelScenario);
        $model->visibility = Journal::VISIBILITY_JUST_ME;

        $model->populateRelation('task', $task);
        $model->populateRelation('journalTypes', [JournalType::findOne(JournalType::TASK_JOURNAL_TYPE)]);

        // Проверка доступа
        $this->checkAccess($model);

        $model->user_id = Yii::$app->user->id;
        $model->status = Journal::STATUS_DRAFT;
        $model->subject = 'Новая запись от '.date('d.m.Y');

        if ($model->save()) {
            TaskUser::updateAll(['journal_id'=>$model->id], ['user_id'=>Yii::$app->user->id, 'task_id'=>$task->id]);
            return $this->controller->redirect(Url::toRoute(['update', 'id' => $model->id]));
        }
        else {
            print_r($model->errors); die();
            return $this->controller->goBack(Url::toRoute([$this->returnUrl]));
        }
    }
}
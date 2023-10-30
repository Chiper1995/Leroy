<?php
namespace common\models\notifications;


use common\models\Task;
use common\models\User;
use yii\base\Exception;
/**
 * Class TaskNotification
 * @package common\models\notifications
 *
 * @property integer $task_id
 * @property Task $task
 * @property integer $init_user_id
 * @property User $initUser
 */
class TaskNotification extends Notification
{
    public function getTask()
    {
        return $this->hasOne(Task::className(), ['id' => 'task_id']);
    }

    public function getInitUser()
    {
        return $this->hasOne(User::className(), ['id' => 'init_user_id']);
    }

    /**
     * @param \yii\base\Event $event
     * @return TaskNotification
     * @throws Exception
     */
    static public function createFromEvent($event)
    {
        if ((!isset($event->sender['task']))or(!(($task = $event->sender['task']) instanceof Task)))
            throw new Exception("Параметр sender должен содержать экземпляр типа \"" . Task::className() . "\"");

        if ((!isset($event->sender['user']))or(!(($user = $event->sender['user']) instanceof User)))
            throw new Exception("Параметр data['user'] должен содержать экземпляр типа \"" . User::className() . "\"");

        $notificationClass = static::className();
        /**@var self $notification*/
        $notification = new $notificationClass(['task_id' => $task->id, 'init_user_id' => $user->id]);
        $notification->populateRelation('task', $task);
        $notification->populateRelation('initUser', $user);
        return $notification;
    }

    public function getParamsForCheckPermission()
    {
        return ['task' => $this->task, 'user' => $this->initUser];
    }
}
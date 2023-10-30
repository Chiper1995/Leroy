<?php
namespace common\models\notifications;

use common\models\UserNotification;
use common\rbac\Rights;
use common\models\NotificationQueue;
use Yii;

class TaskOnAddNotification extends TaskNotification
{
    static public $RIGHT = Rights::SHOW_TASK_ON_ADD_TO_ME_NOTIFICATION;

    static protected $TYPE = 'TaskOnAddNotification';

    /**
     * @return string
     */
    public static function getTYPE()
    {
        return self::$TYPE;
    }

        public function afterSave($insert, $changedAttributes)
        {
            if ($insert) {
                $queue = new NotificationQueue();
                $queue->task_id = $this->task_id;
                $queue->user_id = $this->init_user_id;
                $queue->save();

                Yii::$app->db->createCommand()
                    ->insert(UserNotification::tableName(), ['user_id' => $this->init_user_id, 'notification_id' => $this->id, 'viewed' => 0])
                    ->execute();
            }
            $grandParent = get_parent_class(get_parent_class(get_parent_class($this)));
            $grandParent::afterSave($insert, $changedAttributes);
        }

}
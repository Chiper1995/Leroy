<?php


namespace common\models\notifications;


use common\models\Earnings;
use common\models\User;
use common\models\UserNotification;
use common\rbac\Rights;
use Yii;

/**
 * Class UserOnChangePointsAddNotification
 * @package common\models\notifications
 * @property integer $init_user_id
 */
class UserOnChangePointsAddNotification extends Notification
{
    public $object_id;
    static public $RIGHT = Rights::SHOW_POINTS_EARNING;

    static protected $TYPE = 'UserOnChangePointsAddNotification';

    /**
     * @param \yii\base\Event $event
     * @return Notification
     */
    static public function createFromEvent($event)
    {
        $object_id = $event->sender->id;
        $notificationClass = static::className();
        $notification = new $notificationClass([
            'init_user_id' => isset($event->sender->family_id) ? $event->sender->family_id : null,
            'object_id' => isset($object_id) ? $object_id : null,
        ]);
        $notification->object_id = $object_id;

        return $notification;
    }

    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
            Yii::$app->db->createCommand()
                ->update(Notification::tableName(), ['object_id' => $this->object_id], 'id = :id', [':id' => $this->id])
                ->execute();
            Yii::$app->db->createCommand()
                ->insert(UserNotification::tableName(), ['user_id' => $this->init_user_id, 'notification_id' => $this->id, 'viewed' => 0])
                ->execute();
        }
    }

    /**
     * Возвращает количество начисленных баллов.
     *
     * @return int
     */
    public function getPoints()
    {
        $notification = Notification::find()->where(['id' => $this->id])->asArray()->one();
        if ($notification === null) {
            return null;
        }

        $model = Earnings::findOne($notification['object_id']);
        if ($model === null) {
            return null;
        }

        return $model->points;
    }

    /**
     * Возвращает комментарий к начисленным баллам.
     *
     * @return string
     */
    public function getDescription()
    {
        $notification = Notification::find()->where(['id' => $this->id])->asArray()->one();
        if ($notification === null) {
            return null;
        }

        $model = Earnings::findOne($notification['object_id']);
        if ($model === null) {
            return null;
        }

        return $model->description;
    }

}
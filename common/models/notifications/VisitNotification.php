<?php
namespace common\models\notifications;


use common\models\Visit;
use yii\base\Exception;

/**
 * Class VisitNotification
 * @package common\models\notifications
 *
 * @property integer $visit_id
 * @property Visit $visit
 */
class VisitNotification extends Notification
{
    public function getVisit()
    {
        return $this->hasOne(Visit::className(), ['id' => 'visit_id']);
    }

    /**
     * @param \yii\base\Event $event
     * @return VisitNotification
     * @throws Exception
     */
    static public function createFromEvent($event)
    {
        if (!($event->sender instanceof Visit))
            throw new Exception("Параметр sender должен содержать экземпляр типа \"" . Visit::className() . "\"");

        $notificationClass = static::className();
        $notification = new $notificationClass(['visit_id' => $event->sender->id]);
        return $notification;
    }

    protected function getParamsForCheckPermission()
    {
        return ['visit' => $this->visit];
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert))
            return false;

        if ($insert) {
            $ids = static::find()
                ->select('id')
                ->andWhere(['visit_id' => $this->visit_id])
                ->column();

            static::setAllViewedByNotificationId($ids);
        }

        return true;
    }
}
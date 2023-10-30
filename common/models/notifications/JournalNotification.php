<?php
namespace common\models\notifications;


use common\models\Journal;
use yii\base\Exception;

/**
 * Class InitUserNotification
 * @package common\models\notifications
 *
 * @property integer $journal_id
 * @property Journal $journal
 */
class JournalNotification extends Notification
{
    public function getJournal()
    {
        return $this->hasOne(Journal::className(), ['id' => 'journal_id']);
    }

    /**
     * @param \yii\base\Event $event
     * @return JournalNotification
     * @throws Exception
     */
    static public function createFromEvent($event)
    {
        if (!($event->sender instanceof Journal))
            throw new Exception("Параметр sender должен содержать экземпляр типа \"" . Journal::className() . "\"");

        $notificationClass = static::className();
        $notification = new $notificationClass(['journal_id' => $event->sender->id]);
        return $notification;
    }

    protected function getParamsForCheckPermission()
    {
        return ['journal' => $this->journal];
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert))
            return false;

        if ($insert) {
            $ids = static::find()
                ->select('id')
                ->andWhere(['journal_id' => $this->journal_id])
                ->column();

            static::setAllViewedByNotificationId($ids);
        }

        return true;
    }
}
<?php
namespace common\models\notifications\dialog;


use common\models\DialogMessage;
use common\models\notifications\Notification;
use common\rbac\Rights;
use yii\base\Exception;

/**
 * Class DialogNewMessageNotification
 * @package common\models\notifications
 *
 * @property integer $journal_comment_id
 * @property DialogMessage $dialogMessage
 */
class DialogNewMessageNotification extends Notification
{
    static public $RIGHT = Rights::SHOW_SEND_MESSAGE_NOTIFICATION;

    static public $TYPE = 'dialog\DialogNewMessageNotification';

    public function getDialogMessage()
    {
        return $this->hasOne(DialogMessage::className(), ['id' => 'dialog_message_id']);
    }

    /**
     * @param \yii\base\Event $event
     * @return DialogNewMessageNotification
     * @throws Exception
     */
    static public function createFromEvent($event)
    {
        if (!($event->sender instanceof DialogMessage))
            throw new Exception("Параметр sender должен содержать экземпляр типа \"" . DialogMessage::className() . "\"");

        $notificationClass = static::className();
        $notification = new $notificationClass(['dialog_message_id' => $event->sender->id]);
        return $notification;
    }

    protected function getParamsForCheckPermission()
    {
        return ['dialogMessage' => $this->dialogMessage];
    }

    public static function setViewedByDialogId($userId, $dialogId)
    {
        return
            \Yii::$app->getDb()->createCommand()
                ->update(
                    '{{%user_notification}}',
                    ['viewed' => true],
                    'user_id = :user_id AND notification_id IN (SELECT id FROM {{%notification}} n WHERE n.dialog_message_id IN (SELECT id FROM {{%dialog_message}} dm WHERE dm.dialog_id = :dialog_id))',
                    [':user_id' => $userId, ':dialog_id' => $dialogId]
                )
                ->execute() > 0;
    }
/*
    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert))
            return false;

        if ($insert) {
            $ids = static::find()
                ->select('id')
                ->andWhere(['journal_comment_id' => $this->journal_comment_id])
                ->column();

            static::setAllViewedByNotificationId($ids);
        }

        return true;
    }
*/
}

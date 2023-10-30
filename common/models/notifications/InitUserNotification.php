<?php
namespace common\models\notifications;


use common\models\User;
use yii\base\Exception;

/**
 * Class InitUserNotification
 * @package common\models\notifications
 *
 * @property integer $init_user_id
 * @property User $initUser
 */
class InitUserNotification extends Notification
{
    public $curatorID;

    public function getInitUser()
    {
        return $this->hasOne(User::className(), ['id' => 'init_user_id']);
    }

    /**
     * @param \yii\base\Event $event
     * @return InitUserNotification
     * @throws Exception
     */
    static public function createFromEvent($event)
    {
        if (!($event->sender instanceof User))
            throw new Exception("Параметр sender должен содержать экземпляр типа \"" . User::className() . "\"");

        $notificationClass = static::className();
        $notification = new $notificationClass([
            'init_user_id' => isset($event->sender->id) ? $event->sender->id : null,
            'curatorID' => isset($event->curatorID) ? $event->curatorID : null,
        ]);
        return $notification;
    }

    protected function getParamsForCheckPermission()
    {
        return ['user' => $this->initUser];
    }

    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
            /**@var $users User[] */
            $users = User::find()->notDeleted()->all();

            foreach ($users as $user) {
                $curatorID = isset($this->curatorID) ? $this->curatorID : null;
                if (
                    \Yii::$app->getAuthManager()->checkAccess($user->id, static::$RIGHT, $this->getParamsForCheckPermission())
                    && $user->id != $curatorID
                ) {
                    $user->link('notifications', $this);
                }
            }
        }

        $this->trigger($insert ? self::EVENT_AFTER_INSERT : self::EVENT_AFTER_UPDATE, new \yii\db\AfterSaveEvent([
            'changedAttributes' => $changedAttributes,
        ]));
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert))
            return false;

        if ($insert) {
            $ids = static::find()
                ->select('id')
                ->andWhere(['init_user_id' => $this->init_user_id])
                ->column();

            static::setAllViewedByNotificationId($ids);
        }

        return true;
    }
}

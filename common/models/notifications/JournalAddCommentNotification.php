<?php
namespace common\models\notifications;

use common\models\Journal;
use common\models\JournalComment;
use common\models\NotificationQueue;
use common\models\User;
use common\models\UserNotification;
use common\rbac\Rights;
use Yii;
use yii\base\Exception;
/**
 * Class JournalAddCommentNotification
 * @package common\models\notifications
 *
 * @property integer $journal_comment_id
 * @property JournalComment $journalComment
 */
class JournalAddCommentNotification extends Notification
{
    static public $RIGHT = Rights::SHOW_JOURNAL_ADD_COMMENT_NOTIFICATION;

    static public $TYPE = 'JournalAddCommentNotification';

    public $countCommentUser;
    public $answer_user_id;

    public function getJournalComment()
    {
        return $this->hasOne(JournalComment::className(), ['id' => 'journal_comment_id']);
    }

    /**
     * @param \yii\base\Event $event
     * @return JournalAddCommentNotification
     * @throws Exception
     */
    static public function createFromEvent($event)
    {
        if (!($event->sender instanceof JournalComment))
            throw new Exception("Параметр sender должен содержать экземпляр типа \"" . JournalComment::className() . "\"");

        $journal = Journal::findOne($event->sender->journal_id);
        $answer_user_id = $event->sender->getParent()->one();
        $notificationClass = static::className();

        $notification = new $notificationClass([
            'journal_comment_id' => $event->sender->id,
            'init_user_id' => $journal->user_id,]);
        if($answer_user_id != NULL){
            $notification->answer_user_id = $answer_user_id->user_id;
        }

        return $notification;
    }

    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
            $queue = new NotificationQueue();
            $queue->comment_id = $this->journalComment->id;
            $queue->user_id = $this->init_user_id;
            $queue->save();

            $admins = User::find()
                ->notDeleted()
                ->where(['role' => User::ROLE_ADMINISTRATOR])
                ->all();
            foreach ($admins as $admin) {
                if (Yii::$app->user->id !== $admin->id) {
                    $admin->link('notifications', $this);
                }
            }

            $user = User::find()
                ->notDeleted()
                ->where(['id' => $this->init_user_id])
                ->one();

            $user_answer = User::find()
                ->notDeleted()
                ->where(['id' => $this->answer_user_id])
                ->one();

            if ($this->answer_user_id != NULL)
            {
                if ($user_answer->role != "administrator"
                    || $user_answer->role == "shopModerator" && $user->city_id != $user_answer->city_id
                    || $user_answer->role == "shop" && $user->curator_id != $this->answer_user_id)
                {
                    Yii::$app->db->createCommand()
                        ->update(Notification::tableName(), ['answer_user_id' => $this->answer_user_id], 'id = :id', [':id' => $this->id])
                        ->execute();
                    Yii::$app->db->createCommand()
                        ->insert(UserNotification::tableName(), ['user_id' => $this->answer_user_id, 'notification_id' => $this->id, 'viewed' => 0])
                        ->execute();
                }
            }

            if (Yii::$app->user->id !== $this->init_user_id) {
                Yii::$app->db->createCommand()
                    ->insert(UserNotification::tableName(), ['user_id' => $this->init_user_id, 'notification_id' => $this->id, 'viewed' => 0])
                    ->execute();
            }

            if ($user->curator_id !== NULL && $user->curator_id !== "" && Yii::$app->user->id !== $user->curator_id) {
                Yii::$app->db->createCommand()
                    ->insert(UserNotification::tableName(), ['user_id' => $user->curator_id, 'notification_id' => $this->id, 'viewed' => 0])
                    ->execute();
            }

            $moderators = User::find()
                ->notDeleted()
                ->where(['role' => User::ROLE_SHOP_MODERATOR])
                ->andWhere(['city_id' => $user->city_id])
                ->all();
            foreach ($moderators as $moderator) {
                if (Yii::$app->user->id !== $moderator->id) {
                    $moderator->link('notifications', $this);
                }
            }

        }
        $grandParent = get_parent_class(get_parent_class($this));
        $grandParent::afterSave($insert, $changedAttributes);
    }
    protected function getParamsForCheckPermission()
    {
        return ['journalComment' => $this->journalComment];
    }

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
}
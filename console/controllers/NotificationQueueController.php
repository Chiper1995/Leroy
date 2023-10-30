<?php

namespace console\controllers;

use yii\console\Controller;
use common\models\User;
use common\models\JournalComment;
use common\models\NotificationQueue;
use common\rbac\Rights;
use common\models\notifications\JournalAddCommentNotification;
use common\models\notifications\TaskNotification;
use common\models\notifications\TaskOnAddNotification;
use common\models\UserNotification;
use yii\db\Expression;
use Yii;

class NotificationQueueController extends Controller
{
    public $NOTIFICATION_TIMEOUT = 60;

    public function actionSend()
    {
        $startTime = microtime(true);
        $endTime = 0;
        /**@var $users User[] */
        $users = User::find()
            ->notDeleted()
            ->all();

        $messages = NotificationQueue::find()
            ->where(['success' => 'N'])
            ->orderBy(['id' => SORT_ASC])
            ->all();


        foreach ($messages as $message) {
            if ($message->comment_id) {
                $userNotification = UserNotification::find()
                    ->select(['notification_id' => 'n.id', 'user_id' => '{{%user_notification}}.user_id'])
                    ->rightJoin('{{%notification}} n', 'n.id = notification_id')
                    ->leftJoin('{{%journal_comment}} jc', 'jc.id = n.journal_comment_id')
                    ->where(
                        ['notification_id' => NULL]
                    )
                    ->andWhere(['jc.id' => $message->comment_id])
                    ->orderBy(['notification_id' => SORT_ASC])
                    ->one();

                if (!empty($userNotification) && $userNotification instanceof UserNotification) {
                    $journal = JournalComment::findOne($message->comment_id);
                    $journalAddCommentNotification = JournalAddCommentNotification::findOne($userNotification);
                    echo $journalAddCommentNotification->id . ' notif - comment ' . $message->comment_id . PHP_EOL;

                    $getParamsForCheckPermission = ['journalComment' => $journal];
                    foreach ($users as $user) {
                        if (
                            $userNotification->user_id != $user->getId() &&
                            Yii::$app->getAuthManager()->checkAccess($user->id, JournalAddCommentNotification::$RIGHT, $getParamsForCheckPermission)
                        ) {
                            $user->link('notifications', $journalAddCommentNotification);
                        }
                    }

                }
            } elseif ($message->user_id && $message->task_id) {
                $userNotification = UserNotification::find()
                    ->select(['notification_id' => 'n.id'])
                    ->rightJoin('{{%notification}} n', 'n.id = notification_id')
                    ->where(
                        ['notification_id' => NULL]
                    )
                    ->andWhere(['n.type' => TaskOnAddNotification::getTYPE(), 'n.init_user_id' => $message->user_id, 'n.task_id' => $message->task_id])
                    ->orderBy(['notification_id' => SORT_ASC])
                    ->one();

                if (!empty($userNotification) && $userNotification instanceof UserNotification) {
                    $taskNotification = TaskNotification::findOne($userNotification);
                    $getParamsForCheckPermission = $taskNotification->getParamsForCheckPermission();
                    echo $taskNotification->id . ' notif  - task ' . $message->task_id . ' user ' . $message->user_id . PHP_EOL;
                    if (\Yii::$app->getAuthManager()->checkAccess($message->user_id, TaskOnAddNotification::$RIGHT, $getParamsForCheckPermission)) {
                        $user = User::findOne($message->user_id);
                        $user->link('notifications', $taskNotification);
                    }
                }
            }
            $message->success = 'Y';
            $message->update();
            $endTime = microtime(true) - $startTime;
            if ($endTime > $this->NOTIFICATION_TIMEOUT) {
                break;
            }
        }
    }
}
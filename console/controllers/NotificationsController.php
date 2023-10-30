<?php

namespace console\controllers;

use common\models\notifications\Notification;
use common\models\Journal;
use common\models\JournalComment;
use common\models\User;
use common\models\Task;
use common\models\Visit;
use yii\console\Controller;

class NotificationsController extends Controller
{
    //для разных групп уведомлений необходимо проверять разные данные
    public $journalTypes = ['JournalByTaskOnCheckNotification', 'JournalOnCheckNotification',
        'JournalOnPublishedNotification', 'JournalOnReturnToEditNotification', 'JournalPhotoOnCheckNotification',
        'JournalPhotoOnPublishedNotification', 'JournalPhotoOnReturnToEditNotification'];

    public $userTypes = ['DeleteUserNotification', 'NewUserRegisterNotification', 'UserOnChangeCuratorNotification', 'UserOnChangePointsMinusNotification', 'UserOnChangePointsAddNotification'];

    public $visitTypes = ['VisitAgreedFamilyNotification', 'VisitAgreedNotification', 'VisitCanceledFamilyNotification',
        'VisitCanceledNotification', 'VisitOnAgreementNotification', 'VisitTimeEditedFamilyNotification'];


    public function actionFlushComments()
    {
        /** @var Notification[] $notifications */
        // SELECT * FROM bs_notification a LEFT JOIN bs_journal_comment b ON a.journal_comment_id = b.id WHERE a.type = 'JournalAddCommentNotification' AND b.id IS NULL;
        $notifications = Notification::find()
            ->leftJoin('{{%journal_comment}}', '{{%notification}}.`journal_comment_id` = {{%journal_comment}}.`id`')
            ->where(['type' => 'JournalAddCommentNotification'])
            ->andFilterWhere(['is', '{{%journal_comment}}.`id`', new \yii\db\Expression('null')])
            ->all();
        foreach($notifications as $notification) {
            echo date('r') . ': ' . $notification->id . ' deleted with empty comment.' . "\n";
            $notification->delete();
        }
    }

    public function actionFlushTasks()
    {
        $notifications = Notification::find()
            ->leftJoin('{{%task}}', '{{%notification}}.`task_id` = {{%task}}.`id`')
            ->where(['type' => 'TaskOnAddNotification'])
            ->andFilterWhere(['is', '{{%task}}.`id`', new \yii\db\Expression('null')])
            ->all();
        foreach($notifications as $notification) {
            echo date('r') . ': ' . $notification->id . ' deleted with empty task.' . "\n";
            $notification->delete();
        }
    }

    public function actionFlushJournals()
    {
        $notifications = Notification::find()
            ->leftJoin('{{%journal}}', '{{%notification}}.`journal_id` = {{%journal}}.`id`')
            ->where(['in', 'type', $this->journalTypes])
            ->andFilterWhere(['is', '{{%journal}}.`id`', new \yii\db\Expression('null')])
            ->all();
        foreach($notifications as $notification) {
            echo date('r') . ': ' . $notification->id . ' deleted with empty journal.' . "\n";
            $notification->delete();
        }
    }

    public function actionFlushUsers()
    {
        $notifications = Notification::find()
            ->leftJoin('{{%user}}', '{{%notification}}.`init_user_id` = {{%user}}.`id`')
            ->where(['in', 'type', $this->userTypes])
            ->andFilterWhere(['is', '{{%user}}.`id`', new \yii\db\Expression('null')])
            ->all();
        foreach($notifications as $notification) {
            echo date('r') . ': ' . $notification->id . ' deleted with empty user.' . "\n";
            $notification->delete();
        }
    }

    public function actionFlushVisits()
    {
        $notifications = Notification::find()
            ->leftJoin('{{%visit}}', '{{%notification}}.`visit_id` = {{%visit}}.`id`')
            ->where(['in', 'type', $this->visitTypes])
            ->andFilterWhere(['is', '{{%visit}}.`id`', new \yii\db\Expression('null')])
            ->all();
        foreach($notifications as $notification) {
            echo date('r') . ': ' . $notification->id . ' deleted with empty visit.' . "\n";
            $notification->delete();
        }
    }

    public function actionClean()
    {
        $this->actionFlushComments();
        $this->actionFlushTasks();
        $this->actionFlushJournals();
        $this->actionFlushUsers();
        $this->actionFlushVisits();
    }
}

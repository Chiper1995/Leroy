<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 15.07.2018
 * Time: 16:13
 */

namespace console\controllers;

use common\models\Journal;
use common\models\notifications\Notification;
use common\models\User;
use common\models\UserViewJournal;
use yii\console\Controller;
use yii\helpers\ArrayHelper;

class JournalController extends Controller
{
    public function actionAutoPublish()
    {
        /** @var Journal[] $journals */
        $journals = Journal::find()
            ->alias('j')
            ->joinWith(['user u'])
            ->with(['linkedNotifications'])
            ->onCheck('j')
            ->andWhere(['u.is_prof' => 1])
            ->andWhere(['<=', 'j.updated_at', time()-86400])
            ->all();

        $publishedCounter = 0;
        $notificationIds = [];
        foreach ($journals as $journal) {

            $notificationIds = array_merge(
                $notificationIds,
                ArrayHelper::getColumn(
                    array_filter(
                        $journal->linkedNotifications,
                        function (Notification $notification) {
                            return $notification->type == 'JournalOnCheckNotification';
                        }
                    ),
                    'id'
                )
            );

            $journal->publish();
			$publishedCounter++;
        }

        if (!empty($notificationIds))
            Notification::setAllViewedByNotificationId($notificationIds);

        echo date('r') . ': ' . $publishedCounter . ' journal records have been published' . "\n";
    }

    /**
     * Автоматическая публикация записей заданий через 72 часа после того, как она была отправлена на проверку (Journal::STATUS_ON_CHECK),
     * если задание добавил сотрудник с ролью: "Маркетинг", "Закупки", "Администратор"
     */
    public function actionPublish()
    {
        $time = time();
        $time -=  72 * 3600;

        /** @var Journal[] $journals */
        $journals = Journal::find()
            ->joinWith(['task', 'task.creator as c'])
            ->andWhere(['c.role' => [User::ROLE_ADMINISTRATOR, User::ROLE_MARKETING, User::ROLE_PURCHASE]])
            ->andWhere(['bs_journal.status' => Journal::STATUS_ON_CHECK])
            ->andWhere(['<=', 'bs_journal.updated_at', $time])
            ->all();

        foreach ($journals as $journal) {
            $journal->status = Journal::STATUS_PUBLISHED;
            $journal->save(false);
            echo $journal->id, PHP_EOL;
        }
    }

    public function actionSetViewedUserJournals()
    {
        /** @var Journal[] $journals */
        $journals = \Yii::$app->db->createCommand('SELECT id, user_id FROM {{%journal}} WHERE status = 3')->queryAll();

        foreach($journals as $index => $journal) {
            $item = new UserViewJournal();
            $item->journalId = $journal['id'];
            $item->userId = $journal['user_id'];
            $item->save();
        }
    }
}
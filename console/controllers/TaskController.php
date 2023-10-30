<?php

namespace console\controllers;

use common\models\notifications\Notification;
use common\models\Task;
use common\models\TaskUser;
use yii\console\Controller;
use yii\helpers\ArrayHelper;

class TaskController extends Controller
{
    public function actionDeleteOverdue()
    {
        /** @var TaskUser[] $taskUsers */
        $taskUsers = TaskUser::find()
            ->joinWith(['task t', 'journal j'])
            ->with(['linkedNotifications'])
            ->active()
            ->emptyJournal('j')
            ->taskOverdue('t')
            ->all();

        $familiesCounter = 0;
        $notificationIds = [];
        foreach ($taskUsers as $taskUser) {
            $notificationIds = array_merge(
                $notificationIds,
                ArrayHelper::getColumn($taskUser->linkedNotifications, 'id')
            );

            $taskUser->status = TaskUser::STATUS_EXPIRED;
            $taskUser->save(false);
			$familiesCounter++;
        }

        if (!empty($notificationIds))
            Notification::setAllViewedByNotificationId($notificationIds);

		echo date('r') . ': ' . $familiesCounter . ' tasks for families have been marked as expired' . "\n";
    }
}
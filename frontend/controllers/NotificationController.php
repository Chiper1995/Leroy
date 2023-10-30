<?php
namespace frontend\controllers;

use common\components\controllers\BaseController;
use common\models\JournalComment;
use common\models\notifications\dialog\DialogNewMessageNotification;
use common\models\notifications\JournalAddCommentNotification;
use common\models\notifications\Notification;
use common\models\User;
use common\models\UserNotification;
use frontend\widgets\NotificationList\NotificationList;
use Yii;
use yii\db\Expression;
use yii\web\NotFoundHttpException;

class NotificationController extends BaseController
{
    public function actionShow($id, $url)
    {
        /**@var Notification $model*/
        if (($model = Notification::findOne($id)) === null) {
            throw new NotFoundHttpException("Notification not found");
        }

        /**@var User $user*/
        $user = \Yii::$app->user->identity;

        $journalId = null;
        $dialogId = null;
        if (!empty($model->dialogMessage) || !empty($model->journalComment)) {
            if ($model instanceof DialogNewMessageNotification)
                $dialogId = $model->dialogMessage->dialog_id;

            if ($model instanceof JournalAddCommentNotification)
                $journalId = $model->journalComment->journal_id;
        }
        /** @var Notification[] $notifications */
        $notifications = Notification::find()
            ->leftJoin('{{%journal_comment}} jc', ['jc.id' => new Expression('{{%notification}}.journal_comment_id')])
            ->leftJoin('{{%dialog_message}} dm', ['dm.id' => new Expression('{{%notification}}.dialog_message_id')])
            ->andWhere(['{{%notification}}.type' => $model->type])
            ->andFilterWhere(['{{%notification}}.init_user_id' => $model->init_user_id])
            ->andFilterWhere(['{{%notification}}.object_id' => $model->object_id])
            ->andFilterWhere(['{{%notification}}.journal_id' => $model->journal_id])
            ->andFilterWhere(['{{%notification}}.task_id' => $model->task_id])
            ->andFilterWhere(['{{%notification}}.visit_id' => $model->visit_id])
            ->andFilterWhere(['jc.journal_id' => $journalId])
            ->andFilterWhere(['dm.dialog_id' => $dialogId])
            ->all();

        // Устанавливаем признак прочтения 
        foreach ($notifications as $notification)
            $notification->setViewed($user->id);

        // Переходим
        return $this->redirect($url);
    }

    public function actionList($lastId)
    {
        //$this->layout = null;
        return NotificationList::widget(['lastId' => $lastId]);
    }

    /**
     * Устанавливаем флаг просмотра уведомления (viewed) в таблице UserNotification
     * @param $id
     * @return bool
     * @throws NotFoundHttpException
     * @throws \yii\db\Exception
     */
    public function actionClose($id, $type = "")
    {
        /**@var Notification $model*/
        if (($model = Notification::findOne($id)) === null) {
            throw new NotFoundHttpException("Notification not found.");
        }

        /**@var User $user*/
        $user = \Yii::$app->user->identity;

        if ($type === "JournalAddCommentNotification")
        {
            $notification = UserNotification::find()
                ->where(["user_id" => Yii::$app->user->id])
                ->andWhere(["viewed" => "0"])
                ->all();
            $notificationId= Notification::findOne($id);
            $notificationJournal = JournalComment::findOne($notificationId["journal_comment_id"]);
            foreach ($notification as $notifications)
            {
                $type = Notification::findOne($notifications->notification_id);
                $journalId = JournalComment::findOne($type["journal_comment_id"]);
                if ($type["type"] === "JournalAddCommentNotification" && $journalId["journal_id"] === $notificationJournal["journal_id"])
                {
                    Yii::$app->db->createCommand()
                        ->update(UserNotification::tableName(), ['viewed' => true], ['user_id' => $user->id, 'notification_id' => $type["id"], 'viewed' => false])
                        ->execute();
                }
            }
        }
        else
        {
            Yii::$app->db->createCommand()
                ->update(UserNotification::tableName(), ['viewed' => true], ['user_id' => $user->id, 'notification_id' => $id, 'viewed' => false])
                ->execute();
        }

        return true;
    }
}

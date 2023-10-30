<?php

namespace frontend\widgets\NotificationList;


use common\models\notifications\dialog\DialogNewMessageNotification;
use common\models\notifications\Notification;
use common\models\notifications\NotificationQuery;
use common\models\User;
use Yii;
use yii\base\Widget;
use yii\helpers\Url;

class NotificationList extends Widget
{
    public $perPage = 10;

    public $lastId;

    protected function getQuery() {
        /**@var User $user*/
        $user = Yii::$app->user->identity;
        /** @var NotificationQuery $query */
        $query = $user->getNewNotifications()
			->andWhere(['NOT IN', 'type', DialogNewMessageNotification::$TYPE])
			->grouped()
			->orderBy('updated_at DESC');

        if ($this->lastId !== null)
            $query->andWhere(['<', '{{%notification}}.id', $this->lastId]);

        return $query;
    }
    /**
     * @return Notification[]
     */
    protected function getNotifications()
    {
        return $this->getQuery()->limit($this->perPage)->all();
    }

    protected function getMoreUrl($lastId) {
        return Url::to(['/notification/list', 'lastId' => $lastId]);
    }

    public function run()
    {
        $query = $this->getQuery();
        $count = $query->count();
        $models = $this->getNotifications();
        $moreUrl = null;

        if ($count > $this->perPage) {
            $moreUrl = $this->getMoreUrl( $models[count($models)-1]->id );
        }

        return $this->render('list', [
            'notifications' => $models,
            'moreUrl' => $moreUrl,
        ]);
    }
}
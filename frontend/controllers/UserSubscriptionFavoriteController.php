<?php


namespace frontend\controllers;

use common\components\controllers\BaseController;
use common\models\Journal;
use Yii;
use common\models\UserSubscriptionFavorite;
use common\models\User;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class UserSubscriptionFavoriteController extends BaseController
{
    /**
     * @param $id
     * @var User $user
     * @return array|UserSubscriptionFavorite
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionFavoriteIt($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = $this->findModel($id);

        /** @var $user User */
        $user = Yii::$app->user->identity;

        if ($model->currentUserFavoriteIt()) {
            UserSubscriptionFavorite::deleteAll(['journalId' => $id, 'userId' => $user->id]);
        } else {
            $item = new UserSubscriptionFavorite();
            $item->journalId = $id;
            $item->userId = $user->id;
            $item->save();
        }

        return [
            'status' => 'success',
            'currentUserFavoriteIt' => $model->currentUserFavoriteIt(),
        ];
    }

    /**
     * @param $id
     * @return Journal
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        $model = Journal::findOne($id);

        if ($model === null) {
            throw new NotFoundHttpException('not found.');
        }

        return $model;
    }

}
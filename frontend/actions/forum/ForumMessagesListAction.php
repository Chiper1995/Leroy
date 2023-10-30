<?php
namespace frontend\actions\forum;

use common\components\actions\ListAction;
use common\models\ForumMessage;
use common\models\ForumTheme;
use frontend\models\forum\ForumMessageSearch;
use Yii;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\log\Logger;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class ForumMessagesListAction
 * @package frontend\actions
 */
class ForumMessagesListAction extends ListAction
{
    /**
     * @param integer $id
     * @return string|void
     * @throws NotFoundHttpException
     * @throws \ErrorException
     * @throws \yii\web\ForbiddenHttpException
     */
    public function run($id = null)
    {
        if (($id === null) || (($parentTheme = ForumTheme::findOne($id)) === null)) {
            throw new NotFoundHttpException(ForumTheme::className()." not found");
        }

        // Проверка доступа
        $this->checkAccess(null);

        /** @var $messageSearchModel ForumMessageSearch */
        $messageSearchModel = new ForumMessageSearch();
        $messageSearchModel->theme_id = $parentTheme->id;
        $messagesDataProvider = $messageSearchModel->search($this->getModelClass(), Yii::$app->request->queryParams, $this->dataProviderConfig);
        self::setScenario($messagesDataProvider->getModels(), $this->modelScenario);

        /**@var ForumMessage $model*/
        $model = new ForumMessage(['theme_id'=>$parentTheme->id, 'user_id'=>Yii::$app->user->id]);
        $model->setScenario('create');

        // Если ajax
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                $model = new ForumMessage(['theme_id'=>$parentTheme->id, 'user_id'=>Yii::$app->user->id]);
                $model->setScenario('create');

                return $this->controller->redirect(Url::current(['#'=>'create-message']));
            }
            else {
                Yii::getLogger()->log(print_r($model->errors, true), Logger::LEVEL_ERROR);
            }
        }

        return $this->controller->render($this->view, [
            'messageSearchModel' => $messageSearchModel,
            'messagesDataProvider' => $messagesDataProvider,
            'parentTheme' => $parentTheme,
            'model' => $model,
        ]);
    }
}
<?php
namespace frontend\actions\forum;

use common\components\actions\CreateAction;
use common\models\ForumTheme;
use frontend\models\forum\ForumMessagesThemeForm;
use ReflectionClass;
use Yii;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\log\Logger;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class ForumMessagesThemeCreateAction extends CreateAction
{
    /**
     * @param integer|null $id Parent theme ID
     * @return array|string|Response
     * @throws NotFoundHttpException
     * @throws \yii\web\ForbiddenHttpException
     */
    public function run($id = null)
    {
        if (($id === null) || (($parentTheme = ForumTheme::findOne($id)) === null)) {
            throw new NotFoundHttpException(ForumTheme::className()." not found");
        }

        if (Yii::$app->request->post('cancel') !== null) {
            return $this->controller->goBack(Url::toRoute([$this->returnUrl, 'id' => $parentTheme->id]));
        }

        /**@var ForumMessagesThemeForm $model*/
        $model = new ForumMessagesThemeForm(['parent_theme_id'=>$parentTheme->id]);
        $model->setScenario($this->modelScenario);

        // Проверка доступа
        $this->checkAccess($model);

        // Если ajax
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                return $this->controller->goBack(Url::toRoute([$this->returnUrl]));
            }
            else {
                Yii::getLogger()->log(print_r($model->errors, true), Logger::LEVEL_ERROR);
            }
        }

        return $this->controller->render($this->view, [
            'model' => $model,
            'parentTheme' => $parentTheme,
        ]);
    }
}
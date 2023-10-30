<?php
namespace frontend\actions\forum;

use common\components\actions\CreateAction;
use common\models\ForumTheme;
use ReflectionClass;
use Yii;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\log\Logger;
use yii\web\Response;

class ForumThemeCreateAction extends CreateAction
{
    /**
     * @param integer|null $id Parent theme ID
     * @return array|string|Response
     * @throws \ErrorException
     * @throws \yii\web\ForbiddenHttpException
     */
    public function run($id = null)
    {
        if (Yii::$app->request->post('cancel') !== null) {
            return $this->controller->goBack(Url::toRoute([$this->returnUrl]));
        }

        /**@var ForumTheme $model*/
        $model = (new ReflectionClass($this->getModelClass()))->newInstance();

        $model->setScenario($this->modelScenario);

        // Проверка доступа
        $this->checkAccess($model);

        // Set parent theme
        if (($id != null) && (($parentTheme = ForumTheme::findOne($id)) != null)) {
            $model->parent_id = $parentTheme->id;
        }

        // Если ajax
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                return $this->controller->goBack(Url::toRoute([$this->returnUrl, 'id' => $model->parent_id]));
            }
            else {
                Yii::getLogger()->log(print_r($model->errors, true), Logger::LEVEL_ERROR);
            }
        }

        return $this->controller->render($this->view, [
            'model' => $model,
        ]);
    }
}
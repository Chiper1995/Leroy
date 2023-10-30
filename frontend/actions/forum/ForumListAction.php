<?php
namespace frontend\actions\forum;

use common\components\actions\ListAction;
use common\models\ForumTheme;
use frontend\models\forum\ForumMessagesThemeSearch;
use frontend\models\forum\ForumThemeSearch;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * Class ForumListAction
 * @package frontend\actions
 */
class ForumListAction extends ListAction
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
        // Проверка доступа
        $this->checkAccess(null);

        /** @var $themesSearchModel ForumThemeSearch */
        $themesSearchModel = new ForumThemeSearch();
        $themesSearchModel->parent_id = $id;
        $themesDataProvider = $themesSearchModel->search($this->getModelClass(), Yii::$app->request->queryParams, $this->dataProviderConfig);
        self::setScenario($themesDataProvider->getModels(), $this->modelScenario);

        /** @var $messagesThemesSearchModel ForumMessagesThemeSearch */
        $messagesThemesSearchModel = new ForumMessagesThemeSearch();
        $messagesThemesSearchModel->parent_id = $id;
        $messagesThemesDataProvider = $messagesThemesSearchModel->search($this->getModelClass(), Yii::$app->request->queryParams, $this->dataProviderConfig);
        self::setScenario($messagesThemesDataProvider->getModels(), $this->modelScenario);

        $parentTheme = ForumTheme::findOne($id);

        return $this->controller->render($this->view, [
            'themesSearchModel' => $themesSearchModel,
            'themesDataProvider' => $themesDataProvider,
            'messagesThemesSearchModel' => $messagesThemesSearchModel,
            'messagesThemesDataProvider' => $messagesThemesDataProvider,
            'parentTheme' => $parentTheme,
        ]);
    }
}
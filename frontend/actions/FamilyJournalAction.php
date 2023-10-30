<?php
namespace frontend\actions;

use common\components\actions\ListAction;
use common\models\User;
use frontend\models\journal\FamilyJournalSearch;
use ReflectionClass;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * Вывод дневника семьи
 * Class FamilyJournalAction
 * @package frontend\actions
 */
class FamilyJournalAction extends ListAction
{
    /**
     * @param integer $id
     * @return string
     * @throws NotFoundHttpException
     * @throws \ErrorException
     * @throws \yii\web\ForbiddenHttpException
     */
    public function run($id = null)
    {
        $family = User::findOne($id);
        if ($family === null) {
            throw new NotFoundHttpException(User::className()." not found");
        }

        // Проверка доступа
        $this->checkAccess($family);

        /** @var $searchModel FamilyJournalSearch */
        $searchModel = (new ReflectionClass($this->searchModelClass))->newInstance();
        $dataProvider = $searchModel->search($family->id, $this->getModelClass(), Yii::$app->request->queryParams, $this->dataProviderConfig);

        self::setScenario($dataProvider->getModels(), $this->modelScenario);

        return $this->controller->render($this->view, [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'family' => $family,
        ]);
    }
}
<?php
namespace frontend\actions;

use common\components\actions\ListAction;
use common\models\User;
use common\models\Journal;
use ReflectionClass;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * Действие для выгрузки журнала в PDF
 * Class FamilyJournalAction
 * @package frontend\actions
 */
class TaskJournalPDFAction extends ListAction
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
        if (Yii::$app->request->post('cancel') !== null) {
            return $this->controller->redirect([$this->returnUrl]);
        }

        /**@var ActiveRecord $model*/
        $model = (new ReflectionClass($this->getModelClass()))->newInstance();
        if (($model = $model::findOne($id)) === null) {
            throw new NotFoundHttpException("{$this->getModelClass()} not found");
        }
        $searchModel = (new ReflectionClass($this->searchModelClass))->newInstance();
        $dataProvider = $searchModel->search(Journal::className(), ['AllJournalSearch' => ['task_filter' => $id]], $this->dataProviderConfig); //
        $model->setScenario($this->modelScenario);

        // Проверка доступа
        $this->checkAccess($model);
        
        return $this->controller->render($this->view, [
            'task' => $model,
            'dataProvider' => $dataProvider
        ]);
    }
}
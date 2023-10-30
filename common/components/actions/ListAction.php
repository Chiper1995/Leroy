<?php
namespace common\components\actions;

use Closure;
use ReflectionClass;
use Yii;
use common\components\ActiveRecord;
use common\models\interfaces\ISearchModel;
use yii\web\ForbiddenHttpException;
use common\models\Journal;

class ListAction extends ModelAction
{
    /**
     * @var string view for action
     */
    public $view = 'list';

    /**
     * @var string scenario for models
     */
    public $modelScenario = 'view';

    /**
     * @var array dataProvider config
     */
    public $dataProviderConfig = [];

    /**
     * @var string filter class
     */
    public $searchModelClass;

    /**
     * @var string filter scenario
     */
    public $searchModelScenario = 'search';

    public function run()
    {
        $this->checkAccess(null);

        /** @var $searchModel ISearchModel */
        $searchModel = (new ReflectionClass($this->searchModelClass))->newInstance();//['scenario' => $this->searchModelScenario]

        $status = isset(Yii::$app->request->queryParams['AllJournalSearch']['status']) ? Yii::$app->request->queryParams['AllJournalSearch']['status'] : null;
        if ($status == Journal::STATUS_ON_CHECK) {
            $this->dataProviderConfig['sort']['defaultOrder'] = ['return_reason'=>SORT_DESC, 'updated_at' => SORT_DESC];
        }

        $dataProvider = $searchModel->search($this->getModelClass(), Yii::$app->request->queryParams, $this->dataProviderConfig);

        if (($dataProvider !== null) && ($this->modelScenario !== null)) {
			self::setScenario($dataProvider->getModels(), $this->modelScenario);
		}

        return $this->controller->render($this->view, [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param ActiveRecord[] $models
     * @param string $scenario
     */
    static public function setScenario($models, $scenario)
    {
        foreach ($models as &$model) {
            $model->setScenario($scenario);
        }
    }

}

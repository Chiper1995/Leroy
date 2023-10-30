<?php
namespace frontend\models;

use common\models\Journal;
use common\models\Task;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;

class MyTasksSearch extends Task
{
    public $status;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return Task::tableName();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'number', 'integerOnly' => true],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param Task $modelClass
     * @param array $params
     * @param array $dataProviderConfig
     *
     * @return ActiveDataProvider
     */
    public function search($modelClass, $params, $dataProviderConfig = [])
    {
        $query = $modelClass::find()->myTasks();

        $dataProvider = new ActiveDataProvider(ArrayHelper::merge($dataProviderConfig, [
            'query' => $query,
        ]));

        if ($this->load($params, StringHelper::basename(get_called_class())) && !$this->validate()) {
            return $dataProvider;
        }

        if (array_search($this->status, [Task::STATUS_IN_PROCESS, Task::STATUS_ON_CHECK,  Task::STATUS_EXECUTED])!==false) {
            $journalStatuses = [
                Task::STATUS_IN_PROCESS => Journal::STATUS_DRAFT,
                Task::STATUS_ON_CHECK => Journal::STATUS_ON_CHECK,
                Task::STATUS_EXECUTED => Journal::STATUS_PUBLISHED,
            ];
            $query->andFilterWhere(['{{%journal}}.status' => $journalStatuses[$this->status]]);
        }
        else if ($this->status == Task::STATUS_NEW) {
            $query->andWhere('journal_id IS NULL');
        }

        return $dataProvider;
    }
}
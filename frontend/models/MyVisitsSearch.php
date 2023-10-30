<?php
namespace frontend\models;

use common\models\Journal;
use common\models\Visit;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;

class MyVisitsSearch extends Visit
{
    public $status;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return Visit::tableName();
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
     * @param Visit $modelClass
     * @param array $params
     * @param array $dataProviderConfig
     *
     * @return ActiveDataProvider
     */
    public function search($modelClass, $params, $dataProviderConfig = [])
    {
        $query = $modelClass::find()->myVisits();

        $dataProvider = new ActiveDataProvider(ArrayHelper::merge($dataProviderConfig, [
            'query' => $query,
        ]));

        if ($this->load($params, StringHelper::basename(get_called_class())) && !$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['status' => $this->status]);

        return $dataProvider;
    }
}
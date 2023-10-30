<?php
namespace frontend\models;

use common\components\PersistSearchStateTrait;
use common\models\Help;
use common\models\Presentation;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;

class PresentationSearch extends Presentation
{
    use PersistSearchStateTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return Presentation::tableName();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['id', 'number', 'integerOnly' => true],

            ['title', 'string', 'max' => 250],
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
     * @param Help $modelClass
     * @param array $params
     * @param array $dataProviderConfig
     *
     * @return ActiveDataProvider
     */
    public function search($modelClass, $params, $dataProviderConfig = [])
    {
        $query = $modelClass::find();
        $dataProvider = new ActiveDataProvider(ArrayHelper::merge($dataProviderConfig, [
            'query' => $query,
        ]));

        // Восстанавливаем состояние
        $this->persistState($dataProvider);

        if ($this->load($params, StringHelper::basename(get_called_class())) && !$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['id' => $this->id]);
        $query->andFilterWhere(['like', 'title', $this->title]);

        return $dataProvider;
    }

    public function init()
    {
        parent::init();
    }
}
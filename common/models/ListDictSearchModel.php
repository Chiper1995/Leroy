<?php
namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\interfaces\ISearchModel;
use yii\helpers\ArrayHelper;
use common\components\ActiveRecord;
use yii\helpers\StringHelper;
use common\components\PersistSearchStateTrait;

/**
 * Class ListDictSearchModel
 * @package common\models
 *
 * @mixin PersistSearchStateTrait
 */
class ListDictSearchModel extends ListDictModel implements ISearchModel
{
    use PersistSearchStateTrait;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'safe'],
            [['id'], 'number', 'integerOnly' => true],
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
     * @param ActiveRecord $modelClass
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
        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
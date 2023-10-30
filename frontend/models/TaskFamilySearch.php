<?php
namespace frontend\models;

use common\models\User;
use common\rbac\Rights;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;

class TaskFamilySearch extends User
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return User::tableName();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username'], 'safe'],
            [['fio'], 'safe'],
            [['city_id'], 'safe'],
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
     * @param integer[] $idList
     * @param User $modelClass
     * @param array $params
     * @param array $dataProviderConfig
     * @return ActiveDataProvider
     */
    public function search($idList, $modelClass, $params, $dataProviderConfig = [])
    {
        $query = $modelClass::find()->notDeleted()->onlyFamilies();

        $dataProvider = new ActiveDataProvider(ArrayHelper::merge($dataProviderConfig, [
            'query' => $query,
        ]));

        if (\Yii::$app->user->can(Rights::SHOW_IN_MY_CITY_FAMILIES)) {
            $query->andWhere(['city_id' => \Yii::$app->user->identity->city_id]);
        }

        $query->andWhere(['id' => $idList]);

        if ($this->load($params, StringHelper::basename(get_called_class())) && !$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['id' => $this->id]);
        $query->andFilterWhere(['city_id' => $this->city_id]);
        $query->andFilterWhere(['like', 'username', $this->username]);
        $query->andFilterWhere(['like', 'fio', $this->fio]);

        return $dataProvider;
    }
}
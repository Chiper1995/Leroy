<?php
namespace frontend\models;

use common\components\PersistSearchStateTrait;
use common\models\User;
use common\rbac\Rights;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;

/**
 * Class FamilySearch
 * @package frontend\models
 *
 * @mixin PersistSearchStateTrait
 */
class FamilySearch extends User
{
    use PersistSearchStateTrait;

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
            [['role'], 'in', 'range' => array_keys(User::getRoleList())],
            [['id'], 'number', 'integerOnly' => true],
            [['status'], 'each', 'rule' => ['in', 'range' => array_keys(User::getStatusList())]],
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
     * @param User $modelClass
     * @param array $params
     * @param array $dataProviderConfig
     *
     * @return ActiveDataProvider
     */
    public function search($modelClass, $params, $dataProviderConfig = [])
    {
        $query = $modelClass::find()->notDeleted()->onlyFamilies()->withPoints('t');

        $query->from('{{%user}} t');
        $query->joinWith(['city'=>function ($q) {$q->from('{{%city}} city');}], true);

        $dataProvider = new ActiveDataProvider(ArrayHelper::merge($dataProviderConfig, [
            'query' => $query,
        ]));

        // Восстанавливаем состояние
        $this->persistState($dataProvider);

        if (\Yii::$app->user->can(Rights::SHOW_IN_MY_CITY_FAMILIES)) {
            $query->andWhere(['t.city_id' => \Yii::$app->user->identity->city_id]);
        }

        if ($this->load($params, StringHelper::basename(get_called_class())) && !$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['t.id' => $this->id]);
        $query->andFilterWhere(['t.status' => $this->status]);
        $query->andFilterWhere(['t.city_id' => $this->city_id]);
        $query->andFilterWhere(['t.role' => $this->role]);
        $query->andFilterWhere(['like', 't.username', $this->username]);
        $query->andFilterWhere(['like', 't.fio', $this->fio]);

        return $dataProvider;
    }
}
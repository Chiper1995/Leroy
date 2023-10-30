<?php
namespace frontend\models;

use common\components\PersistSearchStateTrait;
use common\models\User;
use common\models\Visit;
use common\rbac\Rights;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;

/**
 * Class VisitSearch
 * @package frontend\models
 *
 * @mixin PersistSearchStateTrait
 */
class VisitSearch extends Visit
{
    use PersistSearchStateTrait;

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
            ['id', 'number', 'integerOnly' => true],

            ['date', 'date', 'format'=>'php:Y-m-d'],

            ['user_id', 'safe'],

            [['status'], 'each', 'rule' => ['in', 'range' => array_keys(Visit::getAllStatusNamesList())]],
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
        $query = $modelClass::find()->allVisits();
        $query
            ->from('{{%visit}} t')
            ->joinWith(['user'=>function ($q) {$q->from('{{%user}} user');}], true);

        $dataProvider = new ActiveDataProvider(ArrayHelper::merge($dataProviderConfig, [
            'query' => $query,
        ]));

        // Восстанавливаем состояние
        $this->persistState($dataProvider);

        if (\Yii::$app->user->can(Rights::SHOW_IN_MY_CITY_VISITS)) {
            /**@var User $user*/
            $user = \Yii::$app->user->identity;

            $usersInCitiesQuery = (new Query())->select('user_id')->from('{{%user_city}} uc')->where(['uc.city_id' => $user->getCities()->select('id')->column()]);
            $query
                ->andWhere(['t.creator_id'=>$usersInCitiesQuery])
                ->orWhere(['t.user_id'=>$usersInCitiesQuery]);
        }

        if ($this->load($params, StringHelper::basename(get_called_class())) && !$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['t.id' => $this->id]);
        $query->andFilterWhere(['t.status' => $this->status]);
        $query->andFilterWhere(['t.date' => $this->date]);
        $query->andFilterWhere(['t.user_id' => $this->user_id]);

        return $dataProvider;
    }
}
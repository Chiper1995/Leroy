<?php
namespace frontend\models\user;

use common\components\PersistSearchStateTrait;
use common\models\staticLists\Bool;
use common\models\User;
use common\rbac\Rights;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;

/**
 * Class FamilySearch
 * @package frontend\models\user
 * @mixin PersistSearchStateTrait
 */
class FamilySearch extends User
{
    use PersistSearchStateTrait;

    public $city_id;
    public $object_repair_id;
    public $room_repair_id;
    public $work_repair_id;
    public $goods_shop_id;

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
            [['created_at'], 'safe'], 
            [['email'], 'safe'],
            [['fio'], 'safe'],
            [['city_id'], 'safe'],
            [['object_repair_id', 'room_repair_id', 'work_repair_id', 'goods_shop_id'], 'safe'],
            [['phone'], 'safe'],
            [['role'], 'in', 'range' => array_keys(User::getRoleList())],
            [['id'], 'number', 'integerOnly' => true],
            [['curator_id'], 'each', 'rule' => ['number', 'integerOnly' => true]],
            [['is_prof'], 'each', 'rule' => ['in', 'range' => Bool::getIds()]],
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
        /**@var ActiveQuery $query*/
        $query = $modelClass::find()->notDeleted('t')->onlyFamilies(null, 't')->withPoints('t');

        $query
            ->from('{{%user}} t')
            ->with('cities')
            ->joinWith(['curator'=>function (ActiveQuery $q) {$q->from('{{%user}} curator');}], true);

        $dataProvider = new ActiveDataProvider(ArrayHelper::merge($dataProviderConfig, [
            'query' => $query,
        ]));

        // Восстанавливаем состояние
        $this->persistState($dataProvider);

        if (\Yii::$app->user->can(Rights::SHOW_IN_MY_CITY_FAMILIES)) {
            /**@var User $user*/
            $user = \Yii::$app->user->identity;

            $usersInCitiesQuery = (new Query())->select('user_id')->from('{{%user_city}} uc')->where(['uc.city_id' => $user->getCities()->select('id')->column()]);
            $query->andWhere(['t.id' => $usersInCitiesQuery]);
        }

        if ($this->load($params, StringHelper::basename(get_called_class())) && !$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['t.id' => $this->id]);
        $query->andFilterWhere(['like', 't.email', $this->email]);
        $query->andFilterWhere(['like', 't.phone', $this->phone]);
        $query->andFilterWhere(['t.status' => $this->status]);
        $query->andFilterWhere(['t.role' => $this->role]);
        $query->andFilterWhere(['like', 't.username', $this->username]);
        $query->andFilterWhere(['like', 't.fio', $this->fio]);
        $query->andFilterWhere(['t.curator_id' => $this->curator_id]);
        $query->andFilterWhere(['t.is_prof' => $this->is_prof]);

        if ($this->city_id != null) {
            $usersInCitiesQuery = (new Query())->select('user_id')->from('{{%user_city}} uc')->where(['uc.city_id' => $this->city_id]);
            $query->andWhere(['t.id' => $usersInCitiesQuery]);
        }

        if ($this->object_repair_id != null) {
            $repairObjectsQuery = (new Query())->select('user_id')->from('{{%user_object_repair}} uor')->where(['uor.object_repair_id' => $this->object_repair_id]);
            $query->andWhere(['t.id' => $repairObjectsQuery]);
        }

        if ($this->room_repair_id != null) {
            $repairRoomQuery = (new Query())->select('user_id')->from('{{%user_room_repair}} urr')->where(['urr.room_repair_id' => $this->room_repair_id]);
            $query->andWhere(['t.id' => $repairRoomQuery]);
        }

        if ($this->work_repair_id != null) {
            $repairWorkQuery = (new Query())->select('user_id')->from('{{%user_work_repair}} uwr')->where(['uwr.work_repair_id' => $this->room_repair_id]);
            $query->andWhere(['t.id' => $repairWorkQuery]);
        }

        if ($this->goods_shop_id != null) {
            /** @var $shopQuery \common\models\JournalQuery */
            $shopQuery = \common\models\Journal::find()
                ->getUserId()
                ->byGoodsShop($this->goods_shop_id);

            $query->andWhere(['t.id' => $shopQuery]);
        }
        switch ($this->created_at){
          case "1":
                $query->andWhere(['date(from_unixtime(t.created_at))' => date("Y-m-d",strtotime("today"))]);
                break;
            case "2":
                $query->andWhere(['date(from_unixtime(t.created_at))' => date("Y-m-d",strtotime("yesterday"))]);
                break;
            case "3":
                $query->andWhere(['>=','date(from_unixtime(t.created_at))',date("Y-m-d",strtotime("-2 weeks"))])
                      ->andWhere(['<=','date(from_unixtime(t.created_at))',date("Y-m-d",strtotime("today"))]);
                break;
            case "4":
                $query->andWhere(['>=','date(from_unixtime(t.created_at))',date("Y-m-d",strtotime("-1 month"))])
                      ->andWhere(['<=','date(from_unixtime(t.created_at))',date("Y-m-d",strtotime("today"))]);
                break;
            case "5":
                $query->andWhere(['>=','date(from_unixtime(t.created_at))',date("Y-m-d",strtotime("-1 year"))])
                      ->andWhere(['<=','date(from_unixtime(t.created_at))',date("Y-m-d",strtotime("today"))]);
                break;
        }

        return $dataProvider;
    }
}

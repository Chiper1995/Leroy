<?php
namespace frontend\models\user;

use common\models\User;
use common\rbac\Rights;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;

/**
 * Class FamilySearchForTask
 * @package frontend\models\user
 */
class FamilySearchForTask extends User
{
    public $city_id;
	public $object_repair_id;
	public $curator_id;
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
            [['fio'], 'safe'],
            [['city_id'], 'safe'],
            [['object_repair_id', 'room_repair_id', 'work_repair_id', 'goods_shop_id'], 'safe'],
            [['curator_id'], 'each', 'rule' => ['number', 'integerOnly' => true]],
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
            /**@var User $user*/
            $user = \Yii::$app->user->identity;

            $usersInCitiesQuery = (new Query())->select('user_id')->from('{{%user_city}} uc')->where(['uc.city_id' => $user->getCities()->select('id')->column()]);
            $query->andWhere(['id' => $usersInCitiesQuery]);
        }

        $query->andWhere(['id' => $idList]);

        if ($this->load($params, StringHelper::basename(get_called_class())) && !$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['id' => $this->id]);
        $query->andFilterWhere(['like', 'username', $this->username]);
        $query->andFilterWhere(['like', 'fio', $this->fio]);

        if ($this->city_id != null) {
            $usersInCitiesQuery = (new Query())->select('user_id')->from('{{%user_city}} uc')->where(['uc.city_id' => $this->city_id]);
            $query->andWhere(['id' => $usersInCitiesQuery]);
        }

		if ($this->object_repair_id != null) {
			$repairObjectsQuery = (new Query())->select('user_id')->from('{{%user_object_repair}} uor')->where(['uor.object_repair_id' => $this->object_repair_id]);
			$query->andWhere(['id' => $repairObjectsQuery]);
		}

        if ($this->curator_id != null) {
            $query->andWhere(['curator_id' => $this->curator_id]);
        }

        if ($this->room_repair_id != null) {
            $repairRoomQuery = (new Query())->select('user_id')->from('{{%user_room_repair}} urr')->where(['urr.room_repair_id' => $this->room_repair_id]);
            $query->andWhere(['id' => $repairRoomQuery]);
        }

        if ($this->work_repair_id != null) {
            $repairWorkQuery = (new Query())->select('user_id')->from('{{%user_work_repair}} uwr')->where(['uwr.work_repair_id' => $this->room_repair_id]);
            $query->andWhere(['id' => $repairWorkQuery]);
        }

        if ($this->goods_shop_id != null) {
            /** @var $shopQuery \common\models\JournalQuery */
            $shopQuery = \common\models\Journal::find()
                ->getUserId()
                ->byGoodsShop($this->goods_shop_id);

            $query->andWhere(['id' => $shopQuery]);
        }

        return $dataProvider;
    }
}
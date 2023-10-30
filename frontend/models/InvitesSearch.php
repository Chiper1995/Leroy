<?php
namespace frontend\models;

use common\components\PersistSearchStateTrait;
use common\models\Invite;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;

/**
 * Class InvitesSearch
 * @package frontend\models
 *
 * @mixin PersistSearchStateTrait
 */
class InvitesSearch extends Invite
{
    use PersistSearchStateTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return Invite::tableName();
    }

    public $typeOfRepair;

    public $repairObject;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['id', 'number', 'integerOnly' => true],
			['status', 'each', 'rule' => ['number', 'integerOnly' => true]],
            ['sex', 'each', 'rule' => ['number', 'integerOnly' => true]],
            ['age', 'safe'],
            ['city_id', 'each', 'rule' => ['number', 'integerOnly' => true]],
            ['family', 'each', 'rule' => ['number', 'integerOnly' => true]],
            ['children', 'each', 'rule' => ['number', 'integerOnly' => true]],
            ['repair_status', 'each', 'rule' => ['number', 'integerOnly' => true]],
            ['repair_when_finish', 'each', 'rule' => ['number', 'integerOnly' => true]],
            ['typeOfRepair', 'each', 'rule' => ['number', 'integerOnly' => true]],
            ['repairObject', 'each', 'rule' => ['number', 'integerOnly' => true]],
			['repair_object_other', 'safe'],
			['have_cottage', 'each', 'rule' => ['number', 'integerOnly' => true]],
			['plan_cottage_works', 'each', 'rule' => ['number', 'integerOnly' => true]],
			['who_worker', 'each', 'rule' => ['number', 'integerOnly' => true]],
			['who_chooser', 'each', 'rule' => ['number', 'integerOnly' => true]],
			['who_buyer', 'each', 'rule' => ['number', 'integerOnly' => true]],
			['money', 'each', 'rule' => ['number', 'integerOnly' => true]],
			['distance', 'each', 'rule' => ['number', 'integerOnly' => true]],
			['shop_name', 'safe'],
			['fio', 'safe'],
			['phone', 'safe'],
			['email', 'safe'],
			['city_other', 'safe'],
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
     * @param Invite $modelClass
     * @param array $params
     * @param array $dataProviderConfig
     *
     * @return ActiveDataProvider
     */
    public function search($modelClass, $params, $dataProviderConfig = [])
    {
        $query = $modelClass::find()
			->alias('invite')
			->joinWith(['city AS city'], true);

        $dataProvider = new ActiveDataProvider(ArrayHelper::merge($dataProviderConfig, [
            'query' => $query,
        ]));

        // Восстанавливаем состояние
        $this->persistState($dataProvider);

        if ($this->load($params, StringHelper::basename(get_called_class())) && !$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['invite.id' => $this->id]);
        $query->andFilterWhere(['invite.status' => $this->status]);
		$query->andFilterWhere(['invite.sex' => $this->sex]);
		$query->andFilterWhere(['invite.city_id' => $this->city_id]);
        $query->andFilterWhere(['invite.like', 'age', $this->age]);
        $query->andFilterWhere(['invite.family' => $this->family]);
        $query->andFilterWhere(['invite.children' => $this->children]);
        $query->andFilterWhere(['invite.repair_status' => $this->repair_status]);
        $query->andFilterWhere(['invite.repair_when_finish' => $this->repair_when_finish]);
		$query->andFilterWhere(['invite.like', 'repair_object_other', $this->repair_object_other]);
        $query->andFilterWhere(['invite.have_cottage' => $this->have_cottage]);
        $query->andFilterWhere(['invite.plan_cottage_works' => $this->plan_cottage_works]);
        $query->andFilterWhere(['invite.who_worker' => $this->who_worker]);
        $query->andFilterWhere(['invite.who_chooser' => $this->who_chooser]);
        $query->andFilterWhere(['invite.who_buyer' => $this->who_buyer]);
        $query->andFilterWhere(['invite.money' => $this->money]);
        $query->andFilterWhere(['invite.distance' => $this->distance]);
		$query->andFilterWhere(['like', 'invite.shop_name', $this->shop_name]);
		$query->andFilterWhere(['like', 'invite.fio', $this->fio]);
		$query->andFilterWhere(['like', 'invite.phone', $this->phone]);
		$query->andFilterWhere(['like', 'invite.email', $this->email]);
		$query->andFilterWhere(['like', 'invite.city_other', $this->city_other]);

		if (!empty($this->typeOfRepair)) {
			$query->andFilterWhere(['invite.id' => (new Query())->select('invite_id')->from('{{%invite_type_repair}}')->where(['type_repair_id' => $this->typeOfRepair])]);
		}

		if (!empty($this->repairObject)) {
			$query->andFilterWhere(['invite.id' => (new Query())->select('invite_id')->from('{{%invite_object_repair}}')->where(['object_repair_id' => $this->repairObject])]);
		}

        return $dataProvider;
    }
}
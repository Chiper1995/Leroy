<?php
namespace frontend\models\journal;

use common\models\Goods;
use common\models\Journal;
use common\models\User;
use common\models\WorkRepair;
use common\rbac\Rights;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;

/**
 * Class AllJournalSearch
 * @package frontend\models\journal
 */
class AllJournalSearch extends Journal
{
    public $goods_filter = [];
    public $repairWorks_filter = [];
    public $task_filter = null;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return Journal::tableName();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'number', 'integerOnly' => true],
            ['status', 'in', 'range' => [-1, self::STATUS_PUBLISHED, self::STATUS_ON_CHECK, self::STATUS_DRAFT]],
            ['task_filter', 'number', 'integerOnly' => true],
            ['goods_filter', 'each', 'rule' => ['number', 'integerOnly' => true]],

            ['repairWorks_filter', 'each', 'rule' => ['number', 'integerOnly' => true]],
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
     * @param Journal $modelClass
     * @param array $params
     * @param array $dataProviderConfig
     *
     * @return ActiveDataProvider
     */
    public function search($modelClass, $params, $dataProviderConfig = [])
    {
        $query = $modelClass::find()->from('{{%journal}} journal')->allJournal('journal')->joinWith(['user', 'task'], true)->with('photos');

        $dataProvider = new ActiveDataProvider(
            ArrayHelper::merge(
                $dataProviderConfig,
                [
                    'query' => $query,
                ]
            )
        );

        if (\Yii::$app->user->can(Rights::SHOW_IN_MY_CITY_JOURNALS)) {
            /**@var User $user */
            $user = \Yii::$app->user->identity;

            $usersInCitiesQuery = (new Query())->select('user_id')->from('{{%user_city}} uc')->where(['uc.city_id' => $user->getCities()->select('id')->column()]);
            $query->andWhere(['journal.user_id' => $usersInCitiesQuery]);
        }

        if ($this->load($params, StringHelper::basename(get_called_class())) && !$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['journal.status' => $this->status]);

        if (($this->goods_filter != null) && (count($this->goods_filter) > 0)) {
            $this->populateRelation('goodsLink', Goods::findAll($this->goods_filter));
            $this->goods_filter = ArrayHelper::getColumn(
                $this->goodsLink,
                function ($element) {
                    return $element->id;
                }
            );
            $query->andWhere('EXISTS(SELECT * FROM {{%journal_goods}} jg WHERE jg.journal_id = journal.id AND jg.goods_id IN (' . implode(',', $this->goods_filter) . '))');
        }
        if (($this->task_filter != null)) {
            $query->andFilterWhere(['bs_task.id' => $this->task_filter]);
        }

        if (($this->repairWorks_filter != null) && (count($this->repairWorks_filter) > 0)) {
            $this->populateRelation('repairWorks', WorkRepair::findAll($this->repairWorks_filter));
            $this->repairWorks_filter = ArrayHelper::getColumn(
                $this->repairWorks,
                function ($element) {
                    return $element->id;
                }
            );
            $query->andWhere('EXISTS(SELECT * FROM {{%journal_work_repair}} jwr WHERE jwr.journal_id = journal.id AND jwr.work_repair_id IN (' . implode(',', $this->repairWorks_filter) . '))');
        }

        return $dataProvider;
    }
}
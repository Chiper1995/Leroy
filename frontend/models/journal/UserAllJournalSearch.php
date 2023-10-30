<?php
namespace frontend\models\journal;

use common\models\Journal;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;
use common\models\WorkRepair;

class UserAllJournalSearch extends Journal
{
    public $smartSearch = '';

    public $workRepair;
    public $type;
    public $roomRepair;
    public $city;

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
        return [];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        $scenarios = Model::scenarios();
        $scenarios['default'] = ['workRepair', 'type', 'roomRepair', 'city'];
        return $scenarios;
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
        $query = $modelClass::find()
            ->from('{{%journal}} journal')
            ->userAllJournal('journal')
            ->joinWith('user', true)
            ->with('photos', 'likeUsers');


        $dataProvider = new ActiveDataProvider(ArrayHelper::merge($dataProviderConfig, [
            'query' => $query,
        ]));

        if ($this->load($params, StringHelper::basename(get_called_class())) && !$this->validate()) {
            return $dataProvider;
        }

        $query->joinWith('user.city');
        $query = $this->filterQuery($query);

        return $dataProvider;
    }


    //добавляем в запрос фильтрацию
    protected function filterQuery($query)
    {
        if (!empty($this->workRepair)) {
            $query->andWhere('EXISTS(SELECT * FROM {{%journal_work_repair}} jwr WHERE jwr.journal_id = journal.id AND jwr.work_repair_id IN (' . $this->workRepair . '))');
        }

        if (!empty($this->type)) {
            $query->andWhere('EXISTS(SELECT * FROM {{%journal_journal_type}} jwr WHERE jwr.journal_id = journal.id AND jwr.journal_type_id IN (' . $this->type . '))');
        }

        if (!empty($this->city)) {
            $query->andFilterWhere(['{{%city}}.id' => $this->city]);
        }

        if (!empty($this->roomRepair)) {
            $query->andWhere('EXISTS(SELECT * FROM {{%journal_room_repair}} jwr WHERE jwr.journal_id = journal.id AND jwr.room_repair_id IN (' . $this->roomRepair . '))');
        }

        return $query;
    }
}

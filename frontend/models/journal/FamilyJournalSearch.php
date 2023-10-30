<?php
namespace frontend\models\journal;

use common\models\Journal;
use common\rbac\Rights;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;

class FamilyJournalSearch extends Journal
{
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
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param integer $familyId
     * @param Journal $modelClass
     * @param array $params
     * @param array $dataProviderConfig
     * @return ActiveDataProvider
     */
    public function search($familyId, $modelClass, $params, $dataProviderConfig = [])
    {
        $query = $modelClass::find()->familyJournal($familyId)->with('photos');

        if (!\Yii::$app->user->can(Rights::SHOW_JOURNALS) && !\Yii::$app->user->can(Rights::SHOW_IN_MY_CITY_JOURNALS)) {
            $query->userAllJournal();
        }

        $dataProvider = new ActiveDataProvider(ArrayHelper::merge($dataProviderConfig, [
            'query' => $query,
        ]));

        if ($this->load($params, StringHelper::basename(get_called_class())) && !$this->validate()) {
            return $dataProvider;
        }

        return $dataProvider;
    }
}
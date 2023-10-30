<?php

namespace frontend\models\journal;

use common\models\Journal;
use yii\base\Model;
use yii\bootstrap\Html;
use yii\data\ActiveDataProvider;
use yii\sphinx\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;

class UserAllJournalSmartSearch extends AllJournalSmartSearch
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['smartSearch', 'filter', 'filter' => 'trim'],
            //['smartSearch', 'required'],
            //['smartSearch', 'string', 'min' => 3, 'max' => 200],
            ['workRepair', 'string'],
            ['type', 'string'],
            ['roomRepair', 'string'],
            ['city', 'string'],
        ];
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
        if (!$this->load($params, StringHelper::basename(get_called_class())) || !$this->validate()) {
            return null;
        }

        // Sphinx query
        $query = new Query();
        $query
            ->select([
                'id',
                'SNIPPET(content, QUERY(), \'limit=500\', \'around=200\') AS content_snippet_1',
                'SNIPPET(content, QUERY(), \'limit=600\', \'around=300\') AS content_snippet_2',
                'SNIPPET(subject, QUERY(), \'limit=500\') AS subject_snippet'
            ])
            ->from('journals, journals_delta')
            ->where('status = :status', [':status' => Journal::STATUS_PUBLISHED])
            ->andWhere('visibility = :visibility', [':visibility' => Journal::VISIBILITY_FOR_ALL])
            ->orderBy(['updated_at' => SORT_DESC])
            ->match($this->getMatchExpression());

        $query = parent::filterQuery($query);

        $dataProvider = new ActiveDataProvider(
            ArrayHelper::merge(
                $dataProviderConfig,
                [
                    'query' => $query,
                ]
            )
        );

        // Checking query
        try {
            if ($dataProvider->count === 0) {
                $dataProvider = null;
            }
        } catch (\Exception $e) {
            $dataProvider = null;
            \Yii::$app->session->setFlash('error', Html::icon('alert') . ' Запрос задан неверно');
        }

        return $dataProvider;
    }
}
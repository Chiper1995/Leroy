<?php
namespace frontend\models\journal;

use common\models\Goods;
use common\models\Journal;
use common\models\JournalGoods;
use common\models\User;
use common\rbac\Rights;
use yii\base\Model;
use yii\bootstrap\Html;
use yii\data\ActiveDataProvider;
use yii\sphinx\MatchExpression;
use yii\sphinx\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;

/**
 * Class AllJournalSmartSearch
 * @package frontend\models\journal
 */
class AllJournalSmartSearch extends Journal
{
    public $smartSearch = '';

	public $goods_filter = [];
    public $repairWorks_filter = [];

    public $workRepair;
    public $type;
    public $roomRepair;
    public $city;

    public $typeFilter;
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
			['smartSearch', 'filter', 'filter' => 'trim'],

			['status', 'number', 'integerOnly' => true],
			['status', 'in', 'range' => [-1, self::STATUS_PUBLISHED, self::STATUS_ON_CHECK, self::STATUS_DRAFT]],

			['goods_filter', 'each', 'rule' => ['number', 'integerOnly' => true]],

			['repairWorks_filter', 'each', 'rule' => ['number', 'integerOnly' => true]],

			['workRepair', 'string'],
			['type', 'string'],
			['roomRepair', 'string'],
			['city', 'string'],
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

	public function attributeLabels()
	{
		return ArrayHelper::merge(parent::attributeLabels(), [
			'smartSearch' => 'Поиск по записям'
		]);
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
				'SNIPPET(preparation_purchase, QUERY(), \'limit=500\') AS preparation_purchase_snippet',
				'SNIPPET(store_selection, QUERY(), \'limit=500\') AS store_selection_snippet',
				'SNIPPET(assessment_product, QUERY(), \'limit=500\') AS assessment_product_snippet',
				'SNIPPET(conclusion, QUERY(), \'limit=500\') AS conclusion_snippet',
				'SNIPPET(advice, QUERY(), \'limit=500\') AS advice_snippet',
                                'SNIPPET(subject, QUERY(), \'limit=500\') AS subject_snippet'
			])
			->from('journals, journals_delta')
			->where('status = :status', [':status' => Journal::STATUS_PUBLISHED])
			->orderBy(['updated_at' => SORT_DESC])
			->match($this->getMatchExpression());

		// в своем городе

		if (\Yii::$app->user->can(Rights::SHOW_IN_MY_CITY_JOURNALS)) {
			/**@var User $user */

			$user = \Yii::$app->user->identity;
                        $cities = $user->getCities()->select('id')->column();

                        if (!empty($cities))
				$query->andWhere('city_id IN (' . implode(', ', $cities) . ')');
		}

		//добавляем параметры фильтров к поиску
		$query = $this->filterQuery($query);

/*
	$file = '/tmp/search.txt';
        // Открываем файл для получения существующего содержимого
        $current = "";//file_get_contents($file);
        // Добавляем нового человека в файл
        //print_r($this->_
        $current .= "MYTEST\n".json_encode($query);
        // Пишем содержимое обратно в файл
        file_put_contents($file, $current);
*/


		$dataProvider = new ActiveDataProvider(
			ArrayHelper::merge(
				$dataProviderConfig,
				[
					'query' => $query,
				]
			)
		);

		// Checking query
//		try {
			if ($dataProvider->count === 0) {
				$dataProvider = null;
			}
//		}
//		catch (\Exception $e) {
//			$dataProvider = null;
//			\Yii::$app->session->setFlash('error', Html::icon('alert') . ' Запрос задан неверно');
//		}

//
//        // товары
//        if (($this->goods_filter != null) and (count($this->goods_filter) > 0)) {
//            $this->populateRelation('goodsLink', Goods::findAll($this->goods_filter));
//            $this->goods_filter = ArrayHelper::getColumn(
//                $this->goodsLink,
//                function ($element) {
//                    return $element->id;
//                }
//            );
//            $query->andWhere('EXISTS(SELECT * FROM {{%journal_goods}} jg WHERE jg.journal_id = journal.id AND jg.goods_id IN (' . implode(',', $this->goods_filter) . '))');
//        }
//
//        // работы
//        if (($this->repairWorks_filter != null) and (count($this->repairWorks_filter) > 0)) {
//            $this->populateRelation('repairWorks', WorkRepair::findAll($this->repairWorks_filter));
//            $this->repairWorks_filter = ArrayHelper::getColumn(
//                $this->repairWorks,
//                function ($element) {
//                    return $element->id;
//                }
//            );
//            $query->andWhere('EXISTS(SELECT * FROM {{%journal_work_repair}} jwr WHERE jwr.journal_id = journal.id AND jwr.work_repair_id IN (' . implode(',', $this->repairWorks_filter) . '))');
//        }

        return $dataProvider;
    }

    protected function getMatchExpression()
	{
		$searchString = $this->escapeMatchValue($this->smartSearch);

		$words = array_filter(explode(' ', str_replace('"', '', $searchString)), function($item){return strlen($item) >= 3;});
		if (count($words) > 0) {
			$searchArray = [];
			$suggestions = [];
			foreach ($words as $word) {
				$suggestions[$word][] = $word;
				$wordSuggestions[] = $word;

				$q = new Query();

				// Закомментировал, так как слишком много левых результатов возвращает
				/*$results = $q->createCommand()
					->setSql('CALL SUGGEST(:keyword, \'journals\', 20 as limit, 50 as reject, 2 as delta_len, 1 as max_edits)')
					->bindParam(':keyword', $word)
					->queryAll();

				$wordSuggestions = [];
				foreach ($results as $item) {
					if ($item['distance'] > 0) {
						$wordSuggestions[] = $item['suggest'];
					}
				}*/

				$keywordsData = $q->createCommand()
					->setSql('CALL KEYWORDS(:keywords, \'journals_keywords\', 0 as stats, 1 as fold_lemmas)')
					->bindValue(':keywords', implode(' ', $wordSuggestions))
					->queryAll();

				$uniqueWordSuggestions = [];
				foreach ($keywordsData as $item) {
					$s = (mb_strlen($item['normalized']) > 2) ? $item['normalized'] : '=' . $item['tokenized'];
					if (!isset($uniqueWordSuggestions[$s])) {
						$uniqueWordSuggestions[$s] = 1;
					}
				}

				// + транслит
                $translitData = $q->createCommand()
					->setSql('CALL SUGGEST(:keywords, \'journals_keywords_translit\', 20 as limit, 50 as reject, 1 as delta_len, 2 as max_edits)')
					->bindParam(':keywords', $word)
					->queryAll();

				foreach ($translitData as $item) {
					if (!isset($uniqueWordSuggestions[$item['suggest']])) {
						$uniqueWordSuggestions[$item['suggest']] = 1;
					}
				}
				//

				$suggestions[$word] = array_keys($uniqueWordSuggestions);
			}

			$searchArray[] = $searchString;
			if (count($words) > 1) {
			    $words = array_values($words);
				for ($i = 0; $i < count($words) - 1; $i++) {
					for ($j = $i + 1; $j < count($words); $j++) {
						foreach ($suggestions[$words[$i]] as $suggestion1) {
							foreach ($suggestions[$words[$j]] as $suggestion2) {
								$searchArray[] = strtr($searchString, [$words[$i] => $suggestion1, $words[$j] => $suggestion2]);
							}
						}
					}
				}
			}
			else {
				$word = reset($words);
				foreach ($suggestions[$word] as $suggestion1) {
					$searchArray[] = strtr($searchString, [$word => $suggestion1]);
				}
			}

			$searchString = '(' . implode(')|(', $searchArray) . ')';
		}

		//\Yii::$app->session->setFlash('error', $searchString);

		return new MatchExpression($searchString);
	}

    // I need to allow ",| symbols in keywords that's why I can't use default method
	protected function escapeMatchValue($str)
	{
		return str_replace(
			['\\', '/', '(', ')', '-', '!', '@', '~', '&', '^', '$', '=', '>', '<', "\x00", "\n", "\r", "\x1a"],
			['\\\\', '\\/', '\\(', '\\)', '\\-', '\\!', '\\@', '\\~', '\\&', '\\^', '\\$', '\\=', '\\>', '\\<',  "\\x00", "\\n", "\\r", "\\x1a"],
			$str
		);
	}

	static public function prepareSnippet($snippet1, $snippet2)
	{
		$removeUnfinishedSentences = function($snippet) {
			// Начало сниппета
			if (preg_match('/(\.\.\. )([^\.|\!|\?]*)[\.|\!|\?]\s?(.*)/', $snippet, $matches) && isset($matches[2], $matches[3])) {
				if (($matches[2] < 50) && (strpos($matches[3], '<b>') !== false)) {
					$snippet = $matches[3];
				}
			}

			// Конец сниппета
			if (preg_match('/(.*[\.|\!|\?])\s?([^\.|\!|\?]*)( \.\.\.)/', $snippet, $matches) && isset($matches[1], $matches[2])) {
				if (($matches[2] < 50) && (strpos($matches[1], '</b>') !== false)) {
					$snippet = $matches[1];
				}
			}

			return $snippet;
		};

		$snippet1 = $removeUnfinishedSentences($snippet1);
		$snippet2 = $removeUnfinishedSentences($snippet2);


		return (abs(strlen($snippet1) - 400) <= abs(strlen($snippet2) - 400)) ? $snippet1 : $snippet2;
	}

	//добавляем в запрос сфинкса фильтрацию
	protected function filterQuery($query)
	{
		if (!empty($this->workRepair)) {
		    $query->andWhere('work_repair_id = ' . $this->workRepair);
		}

		if (!empty($this->type)) {
		    $query->andWhere('journal_type_id = ' . $this->type);
		}

		if (!empty($this->city)) {
		    $query->andWhere('city_id = ' . $this->city);
		}

		if (!empty($this->roomRepair)) {
		    $query->andWhere('room_repair_id = ' . $this->roomRepair);
		}

        if (!empty($this->repairWorks_filter)) {
            $query->andWhere('work_repair_id IN (' . implode(',', $this->repairWorks_filter).')');
        }

        if (!empty($this->goods_filter)) {
            $journalGoods = (new \yii\db\Query())
                ->select('journal_id')
                ->distinct()
                ->from('{{%journal_goods}} jg')
                ->where(['jg.goods_id' => $this->goods_filter])
                ->all();
            $query->andWhere('id IN ('. implode(',',array_column($journalGoods, 'journal_id')).')');
        }
		return $query;
	}
}

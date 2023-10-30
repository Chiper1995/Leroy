<?php
namespace frontend\models\user;

use common\components\PersistSearchStateTrait;
use common\models\User;
use common\rbac\Rights;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;

/**
 * Class FamiliesForFamilySearch
 * @package frontend\models\user
 */
class FamiliesForFamilySearch extends User
{
    public $search;

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
            ['search', 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return ['default' => ['search']];
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
        $query = $modelClass::find()->notDeleted('t')->onlyFamilies(null, 't');
        $query->from('{{%user}} t')->with('cities');

        $dataProvider = new ActiveDataProvider(ArrayHelper::merge($dataProviderConfig, [
            'query' => $query,
        ]));

		$query->andWhere(['<>', 't.id', \Yii::$app->user->identity->id]);

        if ($this->load($params, StringHelper::basename(get_called_class())) && !$this->validate()) {
            return $dataProvider;
        }

        if (strlen($this->search) > 0) {
			$query->andWhere(
				[
					'or',
					['like', 't.username', $this->search],
					['like', 't.fio', $this->search],
					['like', 't.family_name', $this->search],
					['t.id' => (new Query())->select('user_id')->from('{{%user_city}} uc')->innerJoin('{{%city}} c', 'c.id = uc.city_id')->where(['like', 'c.name', $this->search])]
				]
			);
		}

		$query->orderBy('t.family_name');

        return $dataProvider;
    }

	public function attributeLabels()
	{
		return [
			'search' => 'Поиск'
		];
	}
}
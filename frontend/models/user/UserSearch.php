<?php
namespace frontend\models\user;

use common\components\PersistSearchStateTrait;
use common\models\User;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\ActiveQueryTrait;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;

/**
 * Class UserSearch
 * @package frontend\models\user
 *
 * @mixin PersistSearchStateTrait
 */
class UserSearch extends User
{
    use PersistSearchStateTrait;

    public $city_id;

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
            [['role'], 'safe'],
            [['id'], 'number', 'integerOnly' => true],
            [['city_id'], 'safe'],
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
        $query = $modelClass::find()->notDeleted()->onlyUsers();

        $dataProvider = new ActiveDataProvider(ArrayHelper::merge($dataProviderConfig, [
            'query' => $query,
        ]));

        // Восстанавливаем состояние
        $this->persistState($dataProvider);

        if ($this->load($params, StringHelper::basename(get_called_class())) && !$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['id' => $this->id]);
        $query->andFilterWhere(['role' => $this->role]);
        $query->andFilterWhere(['like', 'username', $this->username]);
        $query->andFilterWhere(['like', 'fio', $this->fio]);

        if ($this->city_id != null) {
            $usersInCitiesQuery = (new Query())->select('user_id')->from('{{%user_city}} uc')->where(['uc.city_id' => $this->city_id]);
            $query->andWhere(['id' => $usersInCitiesQuery]);
        }

        return $dataProvider;
    }
}
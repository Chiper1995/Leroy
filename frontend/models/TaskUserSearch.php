<?php
namespace frontend\models;

use common\models\Journal;
use common\models\TaskUser;
use common\models\User;
use common\rbac\Rights;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;

/**
 * Class TaskUserSearch
 */
class TaskUserSearch extends TaskUser
{
    public $username;
    public $fio;
    public $city_id;
    public $task_status_id;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return TaskUser::tableName();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'number', 'integerOnly' => true],
            [['username'], 'safe'],
            [['fio'], 'safe'],
            [['city_id'], 'safe'],
            [['task_status_id'], 'safe'],
        ];
    }

    /**
     * @return array
     */
    public static function getAllStatusNamesList()
    {
        return [
            0 => 'Задание не выполнялось',
            Journal::STATUS_PUBLISHED => 'Запись опубликована',
            Journal::STATUS_ON_CHECK => 'Запись на проверке',
            Journal::STATUS_DRAFT => 'Запись в черновиках',
            -TaskUser::STATUS_REFUSED => 'Отказ от выполнения',
            -TaskUser::STATUS_EXPIRED => 'Истек срок',
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
     * @param integer $taskId
     * @param TaskUser $modelClass
     * @param array $params
     * @param array $dataProviderConfig
     * @return ActiveDataProvider
     */
    public function search($taskId, $modelClass, $params, $dataProviderConfig = [])
    {
        $query = $modelClass::find()
            ->alias('taskUser')
            ->joinWith(['user AS user', 'journal AS journal'], true)
            ->andWhere(['task_id' => $taskId])
            ->orderBy([
                'journal.created_at' => SORT_DESC,
              ]);

        $dataProvider = new ActiveDataProvider(ArrayHelper::merge($dataProviderConfig, [
            'query' => $query,
        ]));

        if (\Yii::$app->user->can(Rights::SHOW_IN_MY_CITY_FAMILIES)) {
            /**@var User $user*/
            $user = \Yii::$app->user->identity;

            $usersInCitiesQuery = (new Query())->select('user_id')->from('{{%user_city}} uc')->where(['uc.city_id' => $user->getCities()->select('id')->column()]);
            $query->andWhere(['taskUser.user_id' => $usersInCitiesQuery]);
        }

        if ($this->load($params, StringHelper::basename(get_called_class())) && !$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['taskUser.user_id' => $this->user_id]);
        $query->andFilterWhere(['like', 'user.username', $this->username]);
        $query->andFilterWhere(['like', 'user.fio', $this->fio]);

        if ($this->city_id != null) {
            $usersInCitiesQuery = (new Query())->select('user_id')->from('{{%user_city}} uc')->where(['uc.city_id' => $this->city_id]);
            $query->andWhere(['taskUser.user_id' => $usersInCitiesQuery]);
        }

        $query->andFilterWhere(['IN', '(CASE WHEN journal.id IS NOT NULL THEN journal.status ELSE -taskUser.status END)', $this->task_status_id]);

        return $dataProvider;
    }
}
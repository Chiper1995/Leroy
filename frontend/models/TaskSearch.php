<?php
namespace frontend\models;

use common\components\PersistSearchStateTrait;
use common\models\Task;
use common\models\User;
use common\rbac\Rights;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;

/**
 * Class TaskSearch
 * @package frontend\models
 *
 * @mixin PersistSearchStateTrait
 */
class TaskSearch extends Task
{
    use PersistSearchStateTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return Task::tableName();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['id', 'number', 'integerOnly' => true],
            ['name', 'string', 'max' => 250],
            ['deadline', 'date', 'format'=>'php:Y-m-d'],
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
     * @param Task $modelClass
     * @param array $params
     * @param array $dataProviderConfig
     *
     * @return ActiveDataProvider
     */
    public function search($modelClass, $params, $dataProviderConfig = [])
    {
        $query = $modelClass::find()->allTasks();
        $query->from('{{%task}} t');

        $dataProvider = new ActiveDataProvider(ArrayHelper::merge($dataProviderConfig, [
            'query' => $query,
        ]));

        // Восстанавливаем состояние
        $this->persistState($dataProvider);

        if (\Yii::$app->user->can(Rights::SHOW_IN_MY_CITY_TASKS)) {
            /**@var User $user*/
            $user = \Yii::$app->user->identity;

            $usersInCitiesQuery = (new Query())->select('user_id')->from('{{%user_city}} uc')->where(['uc.city_id' => $user->getCities()->select('id')->column()]);
            $tasksForUsersInCitiesQuery = (new Query())->select('tu.task_id')->from('{{%task_user}} tu')->where(['tu.user_id' => $usersInCitiesQuery]);

            $query
                ->andWhere(['t.creator_id'=>$usersInCitiesQuery])
                ->orWhere(['t.id'=>$tasksForUsersInCitiesQuery]);
        }

        if ($this->load($params, StringHelper::basename(get_called_class())) && !$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['t.id' => $this->id]);
        $query->andFilterWhere(['like', 't.name', $this->name]);
        $query->andFilterWhere(['t.deadline' => $this->deadline]);

        return $dataProvider;
    }
}
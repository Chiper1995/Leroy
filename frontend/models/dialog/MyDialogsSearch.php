<?php
namespace frontend\models\dialog;

use common\components\PersistSearchStateTrait;
use common\models\Dialog;
use common\rbac\Rights;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;

/**
 * Class MyDialogsSearch
 * @package frontend\models\dialog
  */
class MyDialogsSearch extends Dialog
{
    use PersistSearchStateTrait;

    public $users_id;

     /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return Dialog::tableName();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['users_id', 'safe'],
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
        return array(
            'users_id' => 'Фильтр по получателям'
        );
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param Dialog $modelClass
     * @param array $params
     * @param array $dataProviderConfig
     *
     * @return ActiveDataProvider
     */
    public function search($modelClass, $params, $dataProviderConfig = [])
    {
        $query = $modelClass::find()->myDialogs('t');
        $query->from('{{%dialog}} t');
        $query->joinWith(['author'=>function ($q) {$q->from('{{%user}} author');}], true);

        $dataProvider = new ActiveDataProvider(ArrayHelper::merge($dataProviderConfig, [
            'query' => $query,
        ]));

        // Восстанавливаем состояние
        $this->persistState($dataProvider);

        if ($this->load($params, StringHelper::basename(get_called_class())) && !$this->validate()) {
            return $dataProvider;
        }

        if ((is_array($this->users_id)) && (count($this->users_id) > 0)) {
            $query->andFilterWhere(['t.id' => (new Query())->select('dialog_id')->from('{{%dialog_user}} du')->where(['du.user_id' => $this->users_id])]);
        }

        return $dataProvider;
    }
}
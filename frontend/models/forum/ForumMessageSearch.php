<?php
namespace frontend\models\forum;

use common\components\PersistSearchStateTrait;
use common\models\ForumMessage;
use common\models\ForumTheme;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;

/**
 * Class ForumMessageSearch
 * @package frontend\models
 *
 * @mixin PersistSearchStateTrait
 */
class ForumMessageSearch extends ForumMessage
{
    use PersistSearchStateTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return ForumMessage::tableName();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

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
     * @param ForumMessage $modelClass
     * @param array $params
     * @param array $dataProviderConfig
     *
     * @return ActiveDataProvider
     */
    public function search($modelClass, $params, $dataProviderConfig = [])
    {
        $query = $modelClass::find()->forumThemeMessages($this->theme_id);

        $dataProvider = new ActiveDataProvider(ArrayHelper::merge($dataProviderConfig, [
            'query' => $query,
        ]));

        // Восстанавливаем состояние
        $this->persistState($dataProvider);

        if ($this->load($params, StringHelper::basename(get_called_class())) && !$this->validate()) {
            return $dataProvider;
        }

        return $dataProvider;
    }
}
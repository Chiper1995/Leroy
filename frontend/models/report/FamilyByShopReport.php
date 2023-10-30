<?php
namespace frontend\models\report;

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
 * Class FamilyByShopReport
 * @package frontend\models\report
 * @property string $shop
 * @mixin PersistSearchStateTrait
 */
class FamilyByShopReport extends User
{
    public $shop;

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
            ['shop', 'required'],
            ['shop', 'string', 'min' => 3],
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
        return ArrayHelper::merge(
            parent::attributeLabels(),
            [
                'shop' => 'Название магазина'
            ]
        );
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

        $query
            ->from('{{%user}} t')
            ->with('cities');

        $dataProvider = new ActiveDataProvider(ArrayHelper::merge($dataProviderConfig, [
            'query' => $query,
        ]));

        if (\Yii::$app->user->can(Rights::SHOW_IN_MY_CITY_FAMILIES)) {
            /**@var User $user*/
            $user = \Yii::$app->user->identity;

            $usersInCitiesQuery = (new Query())->select('user_id')->from('{{%user_city}} uc')->where(['uc.city_id' => $user->getCities()->select('id')]); // ->column()
            $query->andWhere(['t.id' => $usersInCitiesQuery]);
        }

        if ($this->load($params, StringHelper::basename(get_called_class())) && !$this->validate()) {
            $query->andWhere('0=1');
            return $dataProvider;
        }

        // Фильтр по магазину
        if (strlen($this->shop) > 0) {
            $query->joinWith([
                'journals' => function (ActiveQuery $query) {
                    $query
                        ->joinWith('goods', false)
                        ->joinWith('goods.goodsShop', false)
                        ->andWhere('({{%goods_shop}}.name LIKE :shop) OR ({{%journal}}.content LIKE :shop)', [':shop' => "%{$this->shop}%"]);
                }
            ], false);
            $query->groupBy('t.id');
        }
        else {
            $query->andWhere('0=1');
        }

        return $dataProvider;
    }
}
<?php
namespace common\models;

use common\components\ActiveRecord;
use paulzi\adjacencylist\AdjacencyListBehavior;
use yii\helpers\ArrayHelper;

/**
 * Class Goods
 * @package common\models
 *
 * @property integer $id
 * @property integer $parent_id
 * @property integer $group
 * @property string $name
 * @property integer $updated_at
 *
 * @mixin AdjacencyListBehavior
 */
class Goods extends ActiveRecord
{
    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                'AdjacencyListBehavior' => [
                    'class' => AdjacencyListBehavior::className(),
                    'sortAttribute' => null,
                ],
            ]
        );
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return [
            ['name', 'required'],
            ['name', 'unique', 'message' => 'Запись с таким наименованием уже есть'],

            ['parent_id', 'number', 'integerOnly' => true],
        ];
    }

    public function scenarios() {
        $scenarios = parent::scenarios();
        $scenarios['create'] = ['name', 'parent_id'];
        $scenarios['update'] = ['name', 'parent_id'];
        return $scenarios;
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'name' => 'Наименование',
            'updated_at' => 'Последнее обновление',
        );
    }

    /**
     * @return GoodsSearch
     */
    public static function find()
    {
        return new GoodsSearch(get_called_class());
    }

}
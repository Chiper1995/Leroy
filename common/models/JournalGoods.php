<?php
namespace common\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Class JournalGoods
 * @package common\models
 * @property integer $journal_id
 * @property integer $goods_id
 * @property integer $quantity
 * @property integer $online
 * @property double $price
 * @property integer $goods_shop_id
 * @property Journal $journal
 * @property Goods $goods
 * @property GoodsShop $goodsShop
 */
class JournalGoods extends ActiveRecord
{
    const SCENARIO_CREATE = 'create';
    const SCENARIO_DRAFT_CREATE = 'draft-create';

    /**
     * @return ActiveQuery
     */
    public function getJournal()
    {
        return $this->hasOne(Journal::className(), ['id' => 'journal_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getGoods()
    {
        return $this->hasOne(Goods::className(), ['id' => 'goods_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getGoodsShop()
    {
        return $this->hasOne(GoodsShop::className(), ['id' => 'goods_shop_id']);
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return [
            ['goods_id', 'required'],
            ['goods_id', 'number', 'integerOnly' => true],

            ['quantity', 'required', 'on' => self::SCENARIO_CREATE],
            ['quantity', 'number', 'integerOnly' => true, 'min'=>1],

            ['online', 'number', 'integerOnly' => true, 'min'=>1],

            ['price', 'required', 'on' => self::SCENARIO_CREATE],
            ['price', 'filter', 'filter' => function($value){return str_replace(' ', '', str_replace(',', '.', $value));}],
            ['price', 'number', 'integerOnly' => false, 'min'=>0.01, 'on' => self::SCENARIO_CREATE],

            ['goods_shop_id', 'required', 'on' => self::SCENARIO_CREATE],
            ['goods_shop_id', 'number', 'integerOnly' => true],
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_CREATE] = ['goods_id', 'online', 'quantity', 'price', 'goods_shop_id'];
        $scenarios[self::SCENARIO_DRAFT_CREATE] = ['goods_id', 'online', 'quantity', 'price', 'goods_shop_id'];
        return $scenarios;
    }

    public function attributeLabels()
    {
        return array(
            'price' => 'Цена',
            'online' => 'Онлайн',
            'quantity' => 'Количество',
            'goods_id' => 'Наименование',
            'goods_shop_id' => 'Где покупались',
        );
    }
}
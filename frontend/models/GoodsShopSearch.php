<?php
namespace frontend\models;

use common\models\ListDictSearchModel;

class GoodsShopSearch extends ListDictSearchModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%goods_shop}}';
    }
}
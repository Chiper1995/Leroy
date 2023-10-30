<?php
namespace frontend\models;

use common\models\ListDictSearchModel;

class CitySearch extends ListDictSearchModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%city}}';
    }
}
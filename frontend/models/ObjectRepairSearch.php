<?php
namespace frontend\models;

use common\models\ListDictSearchModel;

class ObjectRepairSearch extends ListDictSearchModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%object_repair}}';
    }
}
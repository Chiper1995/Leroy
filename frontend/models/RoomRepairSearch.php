<?php
namespace frontend\models;

use common\models\ListDictSearchModel;

class RoomRepairSearch extends ListDictSearchModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%room_repair}}';
    }
}
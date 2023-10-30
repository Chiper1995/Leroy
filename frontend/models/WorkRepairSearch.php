<?php
namespace frontend\models;

use common\models\ListDictSearchModel;

class WorkRepairSearch extends ListDictSearchModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%work_repair}}';
    }
}
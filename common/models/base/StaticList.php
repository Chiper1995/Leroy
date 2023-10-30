<?php

namespace common\models\base;

use common\models\interfaces\IStaticList;
use yii\base\Object;
use yii\helpers\ArrayHelper;

abstract class StaticList extends Object implements IStaticList
{
    public static function getArray()
    {
        return [];
    }

    public static function getList()
    {
        return ArrayHelper::map(static::getArray(), 'id', 'name');
    }

    public static function getIds()
    {
        return ArrayHelper::getColumn(static::getArray(), 'id');
    }

    public static function getName($id)
    {
        return ArrayHelper::getValue(static::getList(), $id, null);
    }
}
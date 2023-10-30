<?php
/**
 * Created by PhpStorm.
 * User: arshatilov
 * Date: 12.03.2018
 * Time: 11:31
 */

namespace common\models\staticLists;

use common\models\base\StaticList;

class Bool extends StaticList
{
    const NO = 0;
    const YES = 1;

    public static function getArray()
    {
        return [
            ['id' => self::NO, 'name' => 'Нет'],
            ['id' => self::YES, 'name' => 'Да'],
        ];
    }
}
<?php

namespace common\models;

use common\components\ActiveRecord;

/**
 * Class Shop
 * @package common\models
 *
 * @property integer $id
 * @property integer $city_id
 * @property string $number
 * @property integer $updated_at
 *
 *  @mixin ShopQuery
 */
class Shop extends ActiveRecord
{
    public static function find()
    {
        return new ShopQuery(get_called_class());
    }
}

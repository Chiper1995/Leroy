<?php
namespace common\models;

/**
 * Class RoomRepair
 * @package common\models
 *
 * @property integer $id
 * @property string $name
 * @property integer $updated_at
 */
class RoomRepair extends ListDictModel
{
    const OTHER_TYPE = 'Другое';

    public static function getOtherRoomTypeId()
    {
        return self::find()->where(['name'=>self::OTHER_TYPE])->one()->id;
    }
}

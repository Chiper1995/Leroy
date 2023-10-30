<?php
namespace frontend\models\registration;

use yii\base\Model;
use Yii;

/**
 * RepairObjects form
 */
class RepairObject extends Model
{
    public $object_repair_list = [];

    public $room_repair_list = [];

    public function attributeLabels()
    {
        return [
            'object_repair_list' => 'Объекты ремонта',
            'room_repair_list' => 'Где будут выполняться работы?',
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['object_repair_list', 'required'],

            ['room_repair_list', 'required'],
        ];
    }
}

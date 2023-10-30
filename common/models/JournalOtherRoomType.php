<?php
namespace common\models;
use yii\db\ActiveRecord;

/**
 * Class RoomRepair
 * @package common\models
 *
 * @property integer $id
 * @property string $name
 * @property integer $updated_at
 */
class JournalOtherRoomType extends ActiveRecord
{

    public function scenarios() {
        $scenarios = parent::scenarios();
        $scenarios['create'] = ['journal_id', 'room'];
        $scenarios['update'] = ['journal_id', 'room'];
        return $scenarios;
    }

    public function attributeLabels()
    {
        return array(
            'journal_id' => 'Дневник',
            'room' => 'Название комнаты',
        );
    }
}

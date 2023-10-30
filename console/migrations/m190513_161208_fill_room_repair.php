<?php

use yii\db\Migration;
use common\models\RoomRepair;

class m190513_161208_fill_room_repair extends Migration
{
    public function up()
    {
        $this->insert('{{%room_repair}}', ['name' => RoomRepair::OTHER_TYPE, 'updated_at' => time()]);
    }

    public function down()
    {
        $this->truncateTable('{{%room_repair}}');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}

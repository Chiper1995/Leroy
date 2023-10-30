<?php

use yii\db\Schema;
use yii\db\Migration;

class m151028_102806_table_user_room_repair extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user_room_repair}}', [
            'user_id' => $this->integer(),
            'room_repair_id' => $this->integer(),
        ], $tableOptions);

        $this->createIndex('user_id_room_repair_id', '{{%user_room_repair}}', ['user_id', 'room_repair_id'], true);
    }

    public function down()
    {
        $this->dropTable('{{%user_room_repair}}');
    }
}

<?php

use yii\db\Migration;

class m190403_093400_table_journal_room_repair extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%journal_room_repair}}', [
            'journal_id' => $this->integer(),
            'room_repair_id' => $this->integer(),
        ], $tableOptions);

        $this->createIndex('journal_id_room_repair_id', '{{%journal_room_repair}}', ['journal_id', 'room_repair_id'], true);
    }

    public function down()
    {
        $this->dropTable('{{%journal_room_repair}}');
    }
}

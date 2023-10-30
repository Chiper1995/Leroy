<?php

use yii\db\Migration;

class m190513_162124_table_journal_other_room_type extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%journal_other_room_type}}', [
            'id' => $this->primaryKey(),
            'journal_id' => $this->integer(),
            'room' => $this->string(),
        ], $tableOptions);

        $this->createIndex('journal_id', '{{%journal_other_room_type}}', ['journal_id']);
    }

    public function down()
    {
        $this->dropTable('{{%journal_other_room_type}}');
    }
}

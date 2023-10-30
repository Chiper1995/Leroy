<?php

use yii\db\Schema;
use yii\db\Migration;

class m151205_215158_table_journal_check_photo extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%journal_check_photo}}', [
            'id' => $this->primaryKey(),
            'journal_id' => $this->integer()->notNull(),
            'photo' => $this->string()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%journal_check_photo}}');
    }
}

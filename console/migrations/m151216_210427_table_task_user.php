<?php

use yii\db\Schema;
use yii\db\Migration;

class m151216_210427_table_task_user extends Migration
{
    public function safeUp()
    {
        try {
            $tableOptions = null;
            if ($this->db->driverName === 'mysql') {
                // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
                $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
            }

            $this->createTable('{{%task_user}}', [
                'task_id' => $this->integer(),
                'user_id' => $this->integer(),
                'journal_id' => $this->integer(),
            ], $tableOptions);

            $this->createIndex('user_id', '{{%task_user}}', ['user_id']);
            $this->createIndex('journal_id', '{{%task_user}}', ['journal_id']);
            $this->createIndex('task_id_user_id', '{{%task_user}}', ['user_id', 'task_id'], true);
        }
        catch (\yii\db\Exception $e) {
            return false;
        }
        return true;
    }

    public function down()
    {
        $this->dropTable('{{%task_user}}');
    }
}

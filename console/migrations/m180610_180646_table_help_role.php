<?php

use yii\db\Migration;

class m180610_180646_table_help_role extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%help_role}}', [
            'help_id' => $this->integer()->notNull(),
            'role' => $this->string()->notNull(),
        ], $tableOptions);

        $this->createIndex('help_id_role', '{{%help_role}}', ['help_id', 'role'], true);
    }

    public function safeDown()
    {
        $this->dropTable('{{%help_role}}');
    }
}

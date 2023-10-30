<?php

use yii\db\Migration;

class m180604_173853_table_journal_like_user extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%journal_like_user}}', [
            'journal_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex('idx_journal_id_user_id', '{{%journal_like_user}}', ['journal_id', 'user_id'], true);
    }

    public function safeDown()
    {
        $this->dropIndex('idx_journal_id_user_id', '{{%journal_like_user}}');
        $this->dropTable('{{%journal_like_user}}');
    }
}

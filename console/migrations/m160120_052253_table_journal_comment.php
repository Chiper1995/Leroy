<?php

use yii\db\Schema;
use yii\db\Migration;

class m160120_052253_table_journal_comment extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%journal_comment}}', [
            'id' => $this->primaryKey(),
            'parent_id' => $this->integer(),
            'journal_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'content' => $this->getDb()->getSchema()->createColumnSchemaBuilder('LONGTEXT')->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%journal_comment}}');
    }
}

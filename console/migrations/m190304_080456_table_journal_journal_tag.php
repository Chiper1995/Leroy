<?php

use yii\db\Migration;

class m190304_080456_table_journal_journal_tag extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%journal_journal_tag}}', [
            'journal_id' => $this->integer(),
            'journal_tag_id' => $this->integer(),
        ], $tableOptions);

        $this->createIndex('journal_id_journal_tag_id', '{{%journal_journal_tag}}', ['journal_id', 'journal_tag_id'], true);
    }

    public function down()
    {
        $this->dropTable('{{%journal_journal_tag}}');
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

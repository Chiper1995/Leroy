<?php

use yii\db\Migration;

class m170508_023323_table_journal_journal_type extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%journal_journal_type}}', [
            'journal_id' => $this->integer(),
            'journal_type_id' => $this->integer(),
        ], $tableOptions);

        $this->createIndex('journal_id_journal_type_id', '{{%journal_journal_type}}', ['journal_id', 'journal_type_id'], true);
    }

    public function down()
    {
        $this->dropTable('{{%journal_journal_type}}');
    }
}

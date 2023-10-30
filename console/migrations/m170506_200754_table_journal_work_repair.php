<?php

use yii\db\Migration;

class m170506_200754_table_journal_work_repair extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%journal_work_repair}}', [
            'journal_id' => $this->integer(),
            'work_repair_id' => $this->integer(),
        ], $tableOptions);

        $this->createIndex('journal_id_work_repair_id', '{{%journal_work_repair}}', ['journal_id', 'work_repair_id'], true);
    }

    public function down()
    {
        $this->dropTable('{{%journal_work_repair}}');
    }
}

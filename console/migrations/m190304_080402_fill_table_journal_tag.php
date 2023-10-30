<?php

use yii\db\Migration;

class m190304_080402_fill_table_journal_tag extends Migration
{
    public function up()
    {
        $this->insert('{{%journal_tag}}', ['id' => 1, 'name' => 'Сделано своими руками', 'updated_at' => time()]);
        $this->insert('{{%journal_tag}}', ['id' => 2, 'name' => 'Сделано с помощью мастера', 'updated_at' => time()]);
    }

    public function down()
    {
        $this->truncateTable('{{%journal_tag}}');
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

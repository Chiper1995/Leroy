<?php

use yii\db\Migration;

class m180709_180218_alter_gift_add_journal_id extends Migration
{
    public function safeUp()
    {
        $this->truncateTable('{{%gift}}');
        $this->addColumn('{{%gift}}', 'journal_id', $this->integer()->notNull());
    }

    public function safeDown()
    {
        $this->dropColumn('{{%gift}}', 'journal_id');
    }
}

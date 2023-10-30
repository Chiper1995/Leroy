<?php

use yii\db\Migration;

class m200319_071830_alter_journal_table_add_published_id extends Migration
{
    public function up()
    {
        $this->addColumn('{{%journal}}', 'published_id', $this->integer()->comment("Опубликователь записи"));
    }

    public function down()
    {
        $this->dropColumn('{{%journal}}', 'published_id');
    }
}

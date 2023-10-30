<?php

use yii\db\Schema;
use yii\db\Migration;

class m151201_201904_table_notification_add_journal_id extends Migration
{
    public function up()
    {
        $this->addColumn('{{%notification}}', 'journal_id', $this->integer());
    }

    public function down()
    {
        $this->dropColumn('{{%notification}}', 'journal_id');
    }
}

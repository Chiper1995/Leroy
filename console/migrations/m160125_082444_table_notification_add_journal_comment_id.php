<?php

use yii\db\Schema;
use yii\db\Migration;

class m160125_082444_table_notification_add_journal_comment_id extends Migration
{
    public function up()
    {
        $this->addColumn('{{%notification}}', 'journal_comment_id', $this->integer());
    }

    public function down()
    {
        $this->dropColumn('{{%notification}}', 'journal_comment_id');
    }
}

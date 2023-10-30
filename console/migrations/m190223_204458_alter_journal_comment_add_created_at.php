<?php

use yii\db\Schema;
use yii\db\Migration;

class m190223_204458_alter_journal_comment_add_created_at extends Migration
{
    public function up()
    {
        $this->addColumn('{{%journal_comment}}', 'created_at', $this->integer()->notNull());
    }

    public function down()
    {
        $this->dropColumn('{{%journal_comment}}', 'created_at');
    }
}

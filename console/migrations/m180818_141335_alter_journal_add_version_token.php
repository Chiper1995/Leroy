<?php

use yii\db\Migration;

class m180818_141335_alter_journal_add_version_token extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%journal}}', 'version_token', $this->string(32));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%journal}}', 'version_token');
    }
}

<?php

use yii\db\Schema;
use yii\db\Migration;

class m151214_193845_alter_journal_add_return_reason extends Migration
{
    public function up()
    {
        $this->addColumn('{{%journal}}', 'return_reason', $this->getDb()->getSchema()->createColumnSchemaBuilder('LONGTEXT'));
    }

    public function down()
    {
        $this->dropColumn('{{%journal}}', 'return_reason');
    }
}

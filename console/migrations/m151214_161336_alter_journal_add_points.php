<?php

use yii\db\Schema;
use yii\db\Migration;

class m151214_161336_alter_journal_add_points extends Migration
{
    public function up()
    {
        $this->addColumn('{{%journal}}', 'points', $this->integer()->defaultValue(0));
    }

    public function down()
    {
        $this->dropColumn('{{%journal}}', 'points');
    }
}

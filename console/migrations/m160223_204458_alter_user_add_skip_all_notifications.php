<?php

use yii\db\Schema;
use yii\db\Migration;

class m160223_204458_alter_user_add_skip_all_notifications extends Migration
{
    public function up()
    {
        $this->addColumn('{{%user}}', 'skip_all_notifications', $this->smallInteger()->defaultValue(0));
    }

    public function down()
    {
        $this->dropColumn('{{%user}}', 'skip_all_notifications');
    }
}

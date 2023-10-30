<?php

use yii\db\Schema;
use yii\db\Migration;

class m151214_162022_alter_user_drop_points extends Migration
{
    public function up()
    {
        $this->dropColumn('{{%user}}', 'points');
    }

    public function down()
    {
        $this->addColumn('{{%user}}', 'points', $this->integer()->defaultValue(0));
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

<?php

use yii\db\Migration;

class m200214_095125_alter_user_table_add_login_count extends Migration
{
    public function up()
    {
        $this->addColumn('{{%user}}', 'login_count', $this->integer()->unsigned()->notNull()->defaultValue(0));
    }

    public function down()
    {
        $this->dropColumn('{{%user}}', 'login_count');
    }
}

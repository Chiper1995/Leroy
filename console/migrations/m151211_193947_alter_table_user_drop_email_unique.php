<?php

use yii\db\Schema;
use yii\db\Migration;

class m151211_193947_alter_table_user_drop_email_unique extends Migration
{
    public function up()
    {
        $this->dropIndex('email', '{{%user}}');
    }

    public function down()
    {
        $this->createIndex('email', '{{%user}}', 'email', true);
    }
}

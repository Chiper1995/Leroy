<?php

use yii\db\Schema;
use yii\db\Migration;

class m151222_195210_alter_table_task_user_add_primary_key extends Migration
{
    public function up()
    {
        $this->addPrimaryKey('task_user_pk', '{{%task_user}}', ['task_id', 'user_id']);
    }

    public function down()
    {
        $this->dropPrimaryKey('task_user_pk', '{{%task_user}}');
    }

}

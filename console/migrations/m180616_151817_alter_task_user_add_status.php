<?php

use yii\db\Migration;

class m180616_151817_alter_task_user_add_status extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%task_user}}', 'status', $this->smallInteger()->notNull()->defaultValue(0));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%task_user}}', 'status');
    }
}

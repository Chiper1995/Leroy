<?php

use yii\db\Migration;

class m180621_092030_alter_table_task_add_deadline extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%task}}', 'deadline', $this->date());
    }

    public function safeDown()
    {
        $this->dropColumn('{{%task}}', 'deadline');
    }
}

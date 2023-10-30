<?php

use yii\db\Schema;
use yii\db\Migration;

class m151219_190908_table_notification_add_task_id extends Migration
{
    public function up()
    {
        $this->addColumn('{{%notification}}', 'task_id', $this->integer());
    }

    public function down()
    {
        $this->dropColumn('{{%notification}}', 'task_id');
    }
}
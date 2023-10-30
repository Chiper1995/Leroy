<?php

use yii\db\Schema;
use yii\db\Migration;

class m151220_080321_alter_table_task_add_creator_id extends Migration
{
    public function up()
    {
        $this->addColumn('{{%task}}', 'creator_id', $this->integer());
    }

    public function down()
    {
        $this->dropColumn('{{%task}}', 'creator_id');
    }
}

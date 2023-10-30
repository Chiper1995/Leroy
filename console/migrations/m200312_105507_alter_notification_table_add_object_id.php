<?php

use yii\db\Migration;

class m200312_105507_alter_notification_table_add_object_id extends Migration
{
    public function up()
    {
        $this->addColumn('{{%notification}}', 'object_id', $this->integer()->comment("Связная сущность"));
    }

    public function down()
    {
        $this->dropColumn('{{%notification}}', 'object_id');
    }
}

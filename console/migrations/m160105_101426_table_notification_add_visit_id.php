<?php

use yii\db\Schema;
use yii\db\Migration;

class m160105_101426_table_notification_add_visit_id extends Migration
{
    public function up()
    {
        $this->addColumn('{{%notification}}', 'visit_id', $this->integer());
    }

    public function down()
    {
        $this->dropColumn('{{%notification}}', 'visit_id');
    }
}

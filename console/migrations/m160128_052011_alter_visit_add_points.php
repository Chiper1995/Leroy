<?php

use yii\db\Schema;
use yii\db\Migration;

class m160128_052011_alter_visit_add_points extends Migration
{
    public function up()
    {
        $this->addColumn('{{%visit}}', 'points', $this->integer()->defaultValue(0));
    }

    public function down()
    {
        $this->dropColumn('{{%visit}}', 'points');
    }
}

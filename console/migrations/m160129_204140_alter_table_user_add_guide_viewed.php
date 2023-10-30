<?php

use yii\db\Schema;
use yii\db\Migration;

class m160129_204140_alter_table_user_add_guide_viewed extends Migration
{
    public function up()
    {
        $this->addColumn('{{%user}}', 'guide_viewed', $this->smallInteger()->defaultValue(0));
    }

    public function down()
    {
        $this->dropColumn('{{%user}}', 'guide_viewed');
    }
}

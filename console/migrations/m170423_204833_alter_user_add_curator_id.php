<?php

use yii\db\Migration;

class m170423_204833_alter_user_add_curator_id extends Migration
{
    public function up()
    {
        $this->addColumn('{{%user}}', 'curator_id', $this->integer()->null());
    }

    public function down()
    {
        $this->dropColumn('{{%user}}', 'curator_id');
    }
}

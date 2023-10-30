<?php

use yii\db\Schema;
use yii\db\Migration;

class m151212_102254_alter_table_user_add_null_city_id extends Migration
{
    public function up()
    {
        $this->alterColumn('{{%user}}', 'city_id', 'INT NULL');
    }

    public function down()
    {
        $this->alterColumn('{{%user}}', 'city_id', 'INT NOT NULL');
    }
}

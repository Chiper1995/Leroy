<?php

use yii\db\Schema;
use yii\db\Migration;

class m151209_180814_table_goods_shop extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%goods_shop}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%goods_shop}}');
    }
}


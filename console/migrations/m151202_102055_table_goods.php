<?php

use yii\db\Schema;
use yii\db\Migration;

class m151202_102055_table_goods extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%goods}}', [
            'id' => $this->primaryKey(),
            'parent_id' => $this->integer(),
            'group' => $this->smallInteger()->notNull()->defaultValue(0),
            'name' => $this->string()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%goods}}');
    }
}

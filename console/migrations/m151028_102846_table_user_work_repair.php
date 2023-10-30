<?php

use yii\db\Schema;
use yii\db\Migration;

class m151028_102846_table_user_work_repair extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user_work_repair}}', [
            'user_id' => $this->integer(),
            'work_repair_id' => $this->integer(),
        ], $tableOptions);

        $this->createIndex('user_id_work_repair_id', '{{%user_work_repair}}', ['user_id', 'work_repair_id'], true);
    }

    public function down()
    {
        $this->dropTable('{{%user_work_repair}}');
    }
}

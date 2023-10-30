<?php

use yii\db\Schema;
use yii\db\Migration;

class m160131_202034_table_user_object_repair extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user_object_repair}}', [
            'user_id' => $this->integer(),
            'object_repair_id' => $this->integer(),
        ], $tableOptions);

        $this->createIndex('user_id_object_repair_id', '{{%user_object_repair}}', ['user_id', 'object_repair_id'], true);

        // Переносим данные
        $this->execute('INSERT INTO {{%user_object_repair}} (user_id, object_repair_id) SELECT id, object_repair_id FROM {{%user}} WHERE object_repair_id > 0');
    }

    public function down()
    {
        $this->dropTable('{{%user_object_repair}}');
    }
}

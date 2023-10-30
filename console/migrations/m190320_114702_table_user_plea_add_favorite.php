<?php

use yii\db\Migration;

class m190320_114702_table_user_plea_add_favorite extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user_plea_add_favorite}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'count' => $this->integer(),
        ], $tableOptions);

        $this->createIndex('user_id', '{{%user_plea_add_favorite}}', ['user_id']);
    }

    public function down()
    {
        $this->dropTable('{{%user_plea_add_favorite}}');
    }
}

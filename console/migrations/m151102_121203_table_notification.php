<?php

use yii\db\Schema;
use yii\db\Migration;

class m151102_121203_table_notification extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%notification}}', [
            'id' => $this->primaryKey(),
            'type' => $this->string()->notNull(),
            'init_user_id' => $this->integer(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%notification}}');
    }
}

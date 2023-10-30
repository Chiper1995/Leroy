<?php

use yii\db\Schema;
use yii\db\Migration;

class m160210_173649_table_dialog_message extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%dialog_message}}', [
            'id' => $this->primaryKey(),
            'dialog_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'message' => $this->getDb()->getSchema()->createColumnSchemaBuilder('LONGTEXT'),
            'created_at' => $this->integer()->notNull(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%dialog_message}}');
    }
}

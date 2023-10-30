<?php

use yii\db\Schema;
use yii\db\Migration;

class m151228_185716_table_visit extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%visit}}', [
            'id' => $this->primaryKey(),
            'date' => $this->date()->notNull(),
            'time' => $this->integer()->notNull(),
            'status' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'creator_id' => $this->integer()->notNull(),
            'description' => $this->getDb()->getSchema()->createColumnSchemaBuilder('LONGTEXT'),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%visit}}');
    }
}

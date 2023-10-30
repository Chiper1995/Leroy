<?php

use yii\db\Migration;

class m160929_082147_table_earnings extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%earnings}}', [
            'id' => $this->primaryKey(),
            'family_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'points' => $this->integer()->notNull(),
            'description' => $this->getDb()->getSchema()->createColumnSchemaBuilder('LONGTEXT'),
            'created_at' => $this->integer()->notNull(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%earnings}}');
    }
}

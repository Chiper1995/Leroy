<?php

use yii\db\Migration;

class m180609_065655_table_gift extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%gift}}', [
            'id' => $this->primaryKey(),
            'from_family_id' => $this->integer()->notNull(),
            'to_family_id' => $this->integer()->notNull(),
            'points' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
        ], $tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable('{{%gift}}');
    }
}

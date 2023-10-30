<?php

use yii\db\Schema;
use yii\db\Migration;

class m151109_104653_table_journal extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%journal}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'subject' => $this->string()->notNull(),
            'content' => $this->getDb()->getSchema()->createColumnSchemaBuilder('LONGTEXT')->notNull(),
            'status' => $this->smallInteger()->notNull()->defaultValue(0),
            'visibility' => $this->smallInteger()->notNull()->defaultValue(0),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%journal}}');
    }
}

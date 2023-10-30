<?php

use yii\db\Schema;
use yii\db\Migration;

class m160427_164910_table_forum_message extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%forum_message}}', [
            'id' => $this->primaryKey(),
            'theme_id' => $this->integer(),
            'is_first' => $this->smallInteger()->defaultValue(0),
            'user_id' => $this->integer(),
            'message' => $this->getDb()->getSchema()->createColumnSchemaBuilder('LONGTEXT')->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%forum_message}}');
    }
}

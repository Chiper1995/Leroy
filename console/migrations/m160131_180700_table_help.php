<?php

use yii\db\Schema;
use yii\db\Migration;

class m160131_180700_table_help extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%help}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
            'content' => $this->getDb()->getSchema()->createColumnSchemaBuilder('LONGTEXT')->notNull(),
            'default' => $this->integer()->notNull()->defaultValue(0),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%help}}');
    }
}

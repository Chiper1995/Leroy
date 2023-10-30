<?php

use yii\db\Migration;

class m191004_103452_table_presentation extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%presentation}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
            'content' => $this->getDb()->getSchema()->createColumnSchemaBuilder('LONGTEXT')->notNull(),
            'file' => $this->string(),
            'help_id' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex('help_id', '{{%presentation}}', ['help_id']);
    }

    public function down()
    {
        $this->dropTable('{{%presentation }}');
    }
}

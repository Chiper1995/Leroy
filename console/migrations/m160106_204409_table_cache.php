<?php

use yii\db\Schema;
use yii\db\Migration;

class m160106_204409_table_cache extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%cache}}', [
            'id' => $this->string(128)->notNull(),
            'expire' => $this->integer(11)->notNull(),
            'data' => $this->getDb()->getSchema()->createColumnSchemaBuilder('LONGBLOB'),
        ], $tableOptions);

        $this->addPrimaryKey('pk_id', '{{%cache}}', ['id']);
        $this->createIndex('idx_expire', '{{%cache}}', ['expire']);
    }

    public function down()
    {
        $this->dropTable('{{%cache}}');
    }
}

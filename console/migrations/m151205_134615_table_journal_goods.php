<?php

use yii\db\Schema;
use yii\db\Migration;

class m151205_134615_table_journal_goods extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%journal_goods}}', [
            'journal_id' => $this->integer(),
            'goods_id' => $this->integer(),
        ], $tableOptions);

        $this->createIndex('journal_id_goods_id', '{{%journal_goods}}', ['journal_id', 'goods_id'], true);
    }

    public function down()
    {
        $this->dropTable('{{%journal_goods}}');
    }
}

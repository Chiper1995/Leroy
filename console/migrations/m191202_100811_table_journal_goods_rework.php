<?php

use yii\db\Migration;

class m191202_100811_table_journal_goods_rework extends Migration
{

    public function safeUp()
    {
        $this->dropIndex('journal_id_goods_id', '{{%journal_goods}}');
        $this->addColumn('{{%journal_goods}}', 'id', $this->integer());
        $this->createIndex('journal_id_goods_id', '{{%journal_goods}}', ['id'], true);
    }

    public function down()
    {
        $this->dropColumn('{{%journal_goods}}', 'id');
        $this->dropIndex('journal_id_goods_id', '{{%journal_goods}}');
        $this->createIndex('journal_id_goods_id', '{{%journal_goods}}', ['journal_id', 'goods_id'], true);
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}

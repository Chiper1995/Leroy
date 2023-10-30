<?php

use yii\db\Schema;
use yii\db\Migration;

class m151209_185010_table_journal_goods_add_fields extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%journal_goods}}', 'quantity', $this->integer());
        $this->addColumn('{{%journal_goods}}', 'price', $this->getDb()->getSchema()->createColumnSchemaBuilder('NUMERIC(15, 2)'));
        $this->addColumn('{{%journal_goods}}', 'goods_shop_id', $this->integer());
    }

    public function safeDown()
    {
        $this->dropColumn('{{%journal_goods}}', 'quantity');
        $this->dropColumn('{{%journal_goods}}', 'price');
        $this->dropColumn('{{%journal_goods}}', 'goods_shop_id');
    }
}

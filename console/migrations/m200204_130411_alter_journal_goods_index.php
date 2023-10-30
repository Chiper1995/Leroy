<?php

use yii\db\Migration;

class m200204_130411_alter_journal_goods_index extends Migration
{
    public function up()
    {
        $this->dropIndex('journal_id_goods_id', '{{%journal_goods}}');
        $this->createIndex('journal_id_goods_id_price', '{{%journal_goods}}', ['journal_id', 'goods_id', 'price'], true);
    }

    public function down()
    {
        $this->dropIndex('journal_id_goods_id_price', '{{%journal_goods}}');
        $this->createIndex('journal_id_goods_id', '{{%journal_goods}}', ['journal_id', 'goods_id'], true);
    }
}

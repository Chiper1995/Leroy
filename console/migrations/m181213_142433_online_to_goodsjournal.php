<?php

use yii\db\Migration;

class m181213_142433_online_to_goodsjournal extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%journal_goods}}', 'online', $this->smallInteger()->notNull()->defaultValue(0));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%journal_goods}}', 'online');
    }
}

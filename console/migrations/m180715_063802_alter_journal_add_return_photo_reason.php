<?php

use yii\db\Migration;

class m180715_063802_alter_journal_add_return_photo_reason extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%journal}}', 'return_photo_reason', $this->getDb()->getSchema()->createColumnSchemaBuilder('LONGTEXT'));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%journal}}', 'return_photo_reason');
    }
}

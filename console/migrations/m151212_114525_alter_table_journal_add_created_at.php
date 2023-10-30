<?php

use yii\db\Schema;
use yii\db\Migration;

class m151212_114525_alter_table_journal_add_created_at extends Migration
{
    public function safeUp()
    {
        try {
            $this->addColumn('{{%journal}}', 'created_at', $this->integer());
            $this->execute('UPDATE {{%journal}} SET created_at = updated_at');
            $this->alterColumn('{{%journal}}', 'created_at', $this->integer()->notNull());
        }
        catch (\yii\base\Exception $e) {
            return false;
        }
        return true;
    }

    public function safeDown()
    {
        try {
            $this->dropColumn('{{%journal}}', 'created_at');
        }
        catch (\yii\base\Exception $e) {
                return false;
            }
        return true;
    }

}

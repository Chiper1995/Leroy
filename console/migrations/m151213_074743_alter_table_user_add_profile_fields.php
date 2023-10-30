<?php

use yii\db\Schema;
use yii\db\Migration;

class m151213_074743_alter_table_user_add_profile_fields extends Migration
{
    public function safeUp()
    {
        try {
            $this->addColumn('{{%user}}', 'created_at', $this->integer());
            $this->execute('UPDATE {{%user}} SET created_at = updated_at');
            $this->alterColumn('{{%user}}', 'created_at', $this->integer()->notNull());

            $this->addColumn('{{%user}}', 'points', $this->integer()->defaultValue(0));
            $this->addColumn('{{%user}}', 'about_family', $this->getDb()->getSchema()->createColumnSchemaBuilder('LONGTEXT'));
            $this->addColumn('{{%user}}', 'about_repair', $this->getDb()->getSchema()->createColumnSchemaBuilder('LONGTEXT'));
            $this->addColumn('{{%user}}', 'photo', $this->string());
            $this->addColumn('{{%user}}', 'family_name', $this->string());
        }
        catch (\yii\db\Exception $e) {
            return false;
        }
        return true;
    }

    public function safeDown()
    {
        try {
            $this->dropColumn('{{%user}}', 'created_at');
            $this->dropColumn('{{%user}}', 'points');
            $this->dropColumn('{{%user}}', 'about_family');
            $this->dropColumn('{{%user}}', 'about_repair');
            $this->dropColumn('{{%user}}', 'photo');
            $this->dropColumn('{{%user}}', 'family_name');
        }
        catch (\yii\db\Exception $e) {
            return false;
        }
        return true;
    }
}

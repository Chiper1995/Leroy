<?php

use yii\db\Schema;
use yii\db\Migration;

class m151213_212520_table_settings extends Migration
{
    public function safeUp()
    {
        try {
            $tableOptions = null;
            if ($this->db->driverName === 'mysql') {
                // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
                $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
            }

            $this->createTable('{{%settings}}', [
                'id' => $this->primaryKey(),
                'name' => $this->string()->notNull(),
                'rus_name' => $this->string()->notNull(),
                'value' => $this->getDb()->getSchema()->createColumnSchemaBuilder('LONGTEXT')->notNull(),
                'updated_at' => $this->integer()->notNull(),
            ], $tableOptions);

            $this->insert('{{%settings}}', ['name'=>'SettingsRewards', 'rus_name'=>'Настройки вознаграждений', ]);
        }
        catch (\yii\db\Exception $e) {
            return false;
        }
        return true;
    }

    public function safeDown()
    {
        try {
            $this->dropTable('{{%settings}}');
        }
        catch (\yii\db\Exception $e) {
            return false;
        }
        return true;
    }
}

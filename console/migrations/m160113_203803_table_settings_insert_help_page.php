<?php

use yii\db\Schema;
use yii\db\Migration;

class m160113_203803_table_settings_insert_help_page extends Migration
{
    public function up()
    {
        $this->insert('{{%settings}}', ['name'=>'SettingsHelpPage', 'rus_name'=>'Страница помощи', ]);
        return true;
    }

    public function down()
    {
        $this->delete('{{%settings}}', ['name'=>'SettingsHelpPage']);
        return true;
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

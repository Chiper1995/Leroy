<?php

use yii\db\Schema;
use yii\db\Migration;

class m160131_194649_table_settings_delete_help_page extends Migration
{
    public function up()
    {
        $this->delete('{{%settings}}', ['name'=>'SettingsHelpPage']);
        return true;
    }

    public function down()
    {
        $this->insert('{{%settings}}', ['name'=>'SettingsHelpPage', 'rus_name'=>'Страница помощи', ]);
        return true;
    }


}

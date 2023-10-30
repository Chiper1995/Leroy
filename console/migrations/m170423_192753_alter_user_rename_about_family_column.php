<?php

use yii\db\Migration;

class m170423_192753_alter_user_rename_about_family_column extends Migration
{
    public function up()
    {
        $this->renameColumn('{{%user}}', 'about_family', 'about_user');
    }

    public function down()
    {
        $this->renameColumn('{{%user}}', 'about_user', 'about_family');
    }
}

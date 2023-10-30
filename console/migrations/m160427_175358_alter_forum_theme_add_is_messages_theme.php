<?php

use yii\db\Schema;
use yii\db\Migration;

class m160427_175358_alter_forum_theme_add_is_messages_theme extends Migration
{
    public function up()
    {
        $this->addColumn('{{%forum_theme}}', 'is_messages_theme', $this->smallInteger()->defaultValue(0));
    }

    public function down()
    {
        $this->dropColumn('{{%forum_theme}}', 'is_messages_theme');
    }
}

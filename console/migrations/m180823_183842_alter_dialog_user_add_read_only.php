<?php

use yii\db\Migration;

class m180823_183842_alter_dialog_user_add_read_only extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%dialog_user}}', 'read_only', $this->smallInteger()->notNull()->defaultValue(0));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%dialog_user}}', 'read_only');
    }
}

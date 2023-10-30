<?php

use yii\db\Migration;

class m180715_094229_alter_user_add_is_prof extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'is_prof', $this->boolean()->notNull()->defaultValue(false));

    }

    public function safeDown()
    {
        $this->dropColumn('{{%user}}', 'is_prof');
    }
}

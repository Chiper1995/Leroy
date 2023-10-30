<?php

use yii\db\Migration;

class m190314_134617_alter_table_user_add_lastvisit_fields extends Migration
{
    public function safeUp()
    {
        try {
            $this->addColumn('{{%user}}', 'last_visit', $this->integer());
            $this->addColumn('{{%user}}', 'visit_notified', $this->integer());
        }
        catch (\yii\db\Exception $e) {
            return false;
        }
        return true;
    }

    public function safeDown()
    {
        try {
            $this->dropColumn('{{%user}}', 'last_visit');
            $this->dropColumn('{{%user}}', 'visit_notified');
        }
        catch (\yii\db\Exception $e) {
            return false;
        }
        return true;
    }
}

<?php

use yii\db\Migration;

class m190827_082403_create extends Migration
{
    public function safeUp()
    {
        try {
            $this->addColumn('{{%user}}', 'second_visit', $this->integer());
        }
        catch (\yii\db\Exception $e) {
            return false;
        }
        return true;
    }

    public function safeDown()
    {
        try {
            $this->dropColumn('{{%user}}', 'second_visit');
        }
        catch (\yii\db\Exception $e) {
            return false;
        }
        return true;
    }
}

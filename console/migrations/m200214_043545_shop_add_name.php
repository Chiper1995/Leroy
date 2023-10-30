<?php

use yii\db\Migration;

class m200214_043545_shop_add_name extends Migration
{
    public function safeUp()
    {
		$this->addColumn('{{%shop}}', 'name', $this->string());
    }

    public function safeDown()
    {
        $this->dropColumn('{{%shop}}', 'name');

        return true;
    }
}

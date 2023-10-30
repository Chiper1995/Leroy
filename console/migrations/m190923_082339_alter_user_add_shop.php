<?php

use yii\db\Migration;

class m190923_082339_alter_user_add_shop extends Migration
{
    public function up()
    {
        $this->addColumn('{{%user}}', 'shop_id', $this->integer());
    }

    public function down()
    {
        $this->dropColumn('{{%user}}', 'shop_id');
    }
}

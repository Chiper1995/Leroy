<?php

use yii\db\Migration;

class m200429_092602_alter_user_table_add_flag_buy_post extends Migration
{
    public function up()
    {
        $this->addColumn('{{%user}}', 'flag_buy_post', $this->integer()->defaultValue(0)->comment('Флаг публикации поста покупки'));
    }

    public function down()
    {
        $this->dropColumn('{{%user}}', 'flag_buy_post');
    }
}

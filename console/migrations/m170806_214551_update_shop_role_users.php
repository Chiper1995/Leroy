<?php

use yii\db\Migration;

class m170806_214551_update_shop_role_users extends Migration
{
    public function up()
    {
        $this->execute('UPDATE {{%user}} SET `role` = \'shopModerator\' WHERE `role` = \'shop\'');
    }

    public function down()
    {
        $this->execute('UPDATE {{%user}} SET `role` = \'shop\' WHERE `role` = \'shopModerator\'');
    }
}

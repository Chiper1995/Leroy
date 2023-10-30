<?php

use yii\db\Migration;

class m200605_083703_alter_notification_table_add_answer_user_id extends Migration
{
    public function up()
    {
        $this->addColumn('{{%notification}}', 'answer_user_id', $this->integer()->comment("Ответ на комментарий"));
    }

    public function down()
    {
        $this->dropColumn('{{%notification}}', 'answer_user_id');
    }
}

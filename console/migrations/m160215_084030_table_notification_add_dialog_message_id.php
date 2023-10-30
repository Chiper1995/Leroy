<?php

use yii\db\Schema;
use yii\db\Migration;

class m160215_084030_table_notification_add_dialog_message_id extends Migration
{
    public function up()
    {
        $this->addColumn('{{%notification}}', 'dialog_message_id', $this->integer());
    }

    public function down()
    {
        $this->dropColumn('{{%notification}}', 'dialog_message_id');
    }
}

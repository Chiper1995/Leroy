<?php

use yii\db\Migration;

class m190916_144642_modify_notification_queue extends Migration
{
    public function up()
    {
        $this->addColumn('{{%notification_queue}}', 'user_id', $this->Integer()->defaultValue(NULL));
        $this->addColumn('{{%notification_queue}}', 'task_id', $this->Integer()->defaultValue(NULL));
    }

    public function down()
    {
        echo "nothing to do.\n";

        return false;
    }

}

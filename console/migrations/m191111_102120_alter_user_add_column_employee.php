<?php

use yii\db\Migration;

class m191111_102120_alter_user_add_column_employee extends Migration
{
    public function up()
    {
        $this->addColumn('{{%user}}', 'is_active_employee', $this->boolean()->defaultValue(false));
        $this->addColumn('{{%user}}', 'register_summ', $this->string());

    }

    public function down()
    {
        $this->dropColumn('{{%user}}', 'is_active_employee');
        $this->dropColumn('{{%user}}', 'register_summ');
    }
}

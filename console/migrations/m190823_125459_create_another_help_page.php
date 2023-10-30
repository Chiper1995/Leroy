<?php

use yii\db\Migration;

class m190823_125459_create_another_help_page extends Migration
{
    public function up()
    {
        $this->insert('{{%help}}', ['title' => 'Меня назначили куратором. Что нужно знать', 'content' =>'<embed src="/include/Меня назначили куратором. Что нужно знать.pdf" width="100%" internalinstanceid="48" style="min-height: 800px;">', 'default' => '0', 'updated_at' => time()]);
    }

    public function down()
    {
        echo "m190823_125459_create_another_help_page cannot be reverted.\n";

        return false;
    }
}

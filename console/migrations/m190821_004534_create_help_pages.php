<?php

use yii\db\Migration;

class m190821_004534_create_help_pages extends Migration
{
    public function up()
    {
        $this->insert('{{%help}}', ['title' => 'Вознаграждение семей', 'content' =>'<embed src="/include/Вознаграждение семей. Как списывать баллы на платформе и товар в магазине.pdf" width="100%" internalinstanceid="48" style="min-height: 800px;">', 'default' => '0', 'updated_at' => time()]);
        $this->insert('{{%help}}', ['title' => 'Использование платформы для пользы магазина', 'content' =>'<embed src="/include/Как использовать платформу для пользы магазина.pdf" width="100%" internalinstanceid="48" style="min-height: 800px;">', 'default' => '0', 'updated_at' => time()]);
        $this->insert('{{%help}}', ['title' => 'Подходящие для моих задач Семьи', 'content' =>'<embed src="/include/Как найти подходящие для моих задач Семьи.pdf" width="100%" internalinstanceid="48" style="min-height: 800px;">', 'default' => '0', 'updated_at' => time()]);
        $this->insert('{{%help}}', ['title' => 'Исследование на платформе', 'content' =>'<embed src="/include/Как провести исследование на платформе.pdf" width="100%" internalinstanceid="48" style="min-height: 800px;">', 'default' => '0', 'updated_at' => time()]);
    }

    public function down()
    {
        echo "m190821_004534_create_help_pages cannot be reverted.\n";

        return false;
    }
}

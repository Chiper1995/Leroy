<?php

use yii\db\Migration;

class m170508_013338_table_journal_type extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%journal_type}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique(),
            'points' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->insert('{{%journal_type}}', ['name' => 'Работы', 'points' => 40]);
        $this->insert('{{%journal_type}}', ['name' => 'Покупки', 'points' => 40]);
        $this->insert('{{%journal_type}}', ['name' => 'Советы', 'points' => 10]);
        $this->insert('{{%journal_type}}', ['name' => 'Вопросы про ремонт', 'points' => 0]);
        $this->insert('{{%journal_type}}', ['name' => 'О проекте', 'points' => 0]);
    }

    public function down()
    {
        $this->dropTable('{{%journal_type}}');
    }
}

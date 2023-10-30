<?php

use yii\db\Migration;

class m180715_135153_table_user_subscription extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user_subscription}}', [
            'user_id' => $this->integer()->notNull(),
            'to_user_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex('user_id_to_user_id', '{{%user_subscription}}', ['user_id', 'to_user_id'], true);
    }

    public function safeDown()
    {
        $this->dropTable('{{%user_subscription}}');
    }
}

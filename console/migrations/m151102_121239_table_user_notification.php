<?php

use yii\db\Schema;
use yii\db\Migration;

class m151102_121239_table_user_notification extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user_notification}}', [
            'user_id' => $this->integer(),
            'notification_id' => $this->integer(),
            'viewed' => $this->boolean()->notNull()->defaultValue(false),
        ], $tableOptions);

        $this->createIndex('user_id', '{{%user_notification}}', ['user_id']);
        $this->createIndex('user_id_notification_id', '{{%user_notification}}', ['user_id', 'notification_id'], true);
    }

    public function down()
    {
        $this->dropTable('{{%user_notification}}');
    }
}

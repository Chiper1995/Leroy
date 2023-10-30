<?php

use yii\db\Migration;

class m190730_145614_notification_queue extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%notification_queue}}', [
            'id' => $this->primaryKey(),
            'comment_id' => $this->integer(),
            'updated_at' => $this->integer(),
            'success' => $this->string(2)->defaultValue("N"),
        ], $tableOptions);

        $this->createIndex('nq_journal_id', '{{%journal_other_room_type}}', ['journal_id']);
    }

    public function down()
    {
        $this->dropTable('{{%journal_other_room_type}}');
    }
}

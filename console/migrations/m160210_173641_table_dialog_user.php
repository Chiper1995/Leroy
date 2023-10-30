<?php

use yii\db\Schema;
use yii\db\Migration;

class m160210_173641_table_dialog_user extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%dialog_user}}', [
            'dialog_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'active' => $this->smallInteger()->notNull()->defaultValue(1),
        ], $tableOptions);

        $this->createIndex('idx_dialog_id_user_id', '{{%dialog_user}}', ['dialog_id', 'user_id'], true);
        $this->createIndex('idx_dialog_id_user_id_active', '{{%dialog_user}}', ['dialog_id', 'user_id', 'active']);
    }

    public function down()
    {
        $this->dropIndex('idx_dialog_id_user_id', '{{%dialog_user}}');
        $this->dropIndex('idx_dialog_id_user_id_active', '{{%dialog_user}}');
        $this->dropTable('{{%dialog_user}}');
    }
}

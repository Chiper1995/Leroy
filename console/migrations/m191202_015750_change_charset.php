<?php

use yii\db\Migration;

class m191202_015750_change_charset extends Migration
{
	private $tables = [
		"cache",
		"city",
		"dialog",
		"dialog_message",
		"dialog_user",
		"earnings",
		"forum_message",
		"forum_theme",
		"gift",
		"goods",
		"goods_shop",
		"help",
		"help_role",
		"journal",
		"journal_check_photo",
		"journal_comment",
		"journal_goods",
		"journal_journal_tag",
		"journal_journal_type",
		"journal_like_user",
		"journal_other_room_type",
		"journal_photo",
		"journal_room_repair",
		"journal_tag",
		"journal_type",
		"journal_work_repair",
		"migration",
		"notification",
		"notification_queue",
		"object_repair",
		"presentation",
		"room_repair",
		"settings",
		"shop",
		"spending",
		"task",
		"task_photo",
		"task_user",
		"user",
		"user_city",
		"user_location",
		"user_notification",
		"user_object_repair",
		"user_plea_add_favorite",
		"user_room_repair",
		"user_subscription",
		"user_work_repair",
		"visit",
		"work_repair",
	];

    public function up()
    {
		//$this->execute('ALTER DATABASE `lm-families` CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci;');

		$this->alterColumn('{{%city}}', 'name', $this->string(100)->notNull()->unique());
		$this->alterColumn('{{%goods_shop}}', 'name', $this->string(100)->notNull()->unique());
		$this->alterColumn('{{%journal_tag}}', 'name', $this->string(100)->notNull()->unique());
		$this->alterColumn('{{%journal_type}}', 'name', $this->string(100)->notNull()->unique());
		$this->alterColumn('{{%object_repair}}', 'name', $this->string(100)->notNull()->unique());
		$this->alterColumn('{{%room_repair}}', 'name', $this->string(100)->notNull()->unique());
		$this->alterColumn('{{%work_repair}}', 'name', $this->string(100)->notNull()->unique());
		$this->alterColumn('{{%help_role}}', 'role', $this->string(100)->notNull());
		$this->alterColumn('{{%user}}', 'username', $this->string(100)->notNull());
		$this->alterColumn('{{%user}}', 'password_reset_token', $this->string(50));

		//$this->dropIndex('name', '{{%task}}');

		foreach ($this->tables as $table) {
			$this->execute('ALTER TABLE {{%' . $table . '}} CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
		}
    }

    public function down()
    {
		//$this->execute('ALTER DATABASE `lm-families` CHARACTER SET = utf8mb4 COLLATE = utf8_unicode_ci;');

		$this->alterColumn('{{%city}}', 'name', $this->string()->notNull()->unique());
		$this->alterColumn('{{%goods_shop}}', 'name', $this->string()->notNull()->unique());
		$this->alterColumn('{{%journal_tag}}', 'name', $this->string()->notNull()->unique());
		$this->alterColumn('{{%journal_type}}', 'name', $this->string()->notNull()->unique());
		$this->alterColumn('{{%object_repair}}', 'name', $this->string()->notNull()->unique());
		$this->alterColumn('{{%room_repair}}', 'name', $this->string()->notNull()->unique());
		$this->alterColumn('{{%work_repair}}', 'name', $this->string()->notNull()->unique());
		$this->alterColumn('{{%help_role}}', 'role', $this->string()->notNull());
		$this->alterColumn('{{%user}}', 'username', $this->string()->notNull());
		$this->alterColumn('{{%user}}', 'password_reset_token', $this->string());

		//$this->createIndex('name', '{{%task}}', 'name', true);

		foreach ($this->tables as $table) {
			$this->execute('ALTER TABLE {{%' . $table . '}} CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;');
		}
    }
}

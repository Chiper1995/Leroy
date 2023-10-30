<?php

use yii\db\Migration;

class m200107_024447_create_table_invite_object_repair extends Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			// http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		}

		$this->createTable('{{%invite_object_repair}}', [
			'invite_id' => $this->integer(),
			'object_repair_id' => $this->smallInteger(),
		], $tableOptions);

		$this->addPrimaryKey('pk_id', '{{%invite_object_repair}}', ['invite_id', 'object_repair_id']);
	}

	public function down()
	{
		$this->dropTable('{{%invite_object_repair}}');
	}
}

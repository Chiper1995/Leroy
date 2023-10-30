<?php

use yii\db\Migration;

class m200206_052229_users_add_invite_id extends Migration
{
	public function up()
	{
		$this->addColumn('{{%user}}', 'invite_id', $this->integer());
		$this->createIndex('idx_invite_id', '{{%user}}', ['invite_id'], true);
		$this->addForeignKey('fk_user_invite_id', '{{%user}}', 'invite_id', '{{%invite}}', 'id', 'RESTRICT', 'RESTRICT');
	}

	public function down()
	{
		$this->dropIndex('idx_invite_id', '{{%user}}');
		$this->dropForeignKey('fk_user_invite_id', '{{%user}}');
		$this->dropColumn('{{%user}}', 'invite_id');
	}
}

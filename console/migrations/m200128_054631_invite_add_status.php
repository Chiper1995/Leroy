<?php

use yii\db\Migration;

class m200128_054631_invite_add_status extends Migration
{
	public function up()
	{
		$this->addColumn('{{%invite}}', 'status', $this->smallInteger()->notNull()->defaultValue(0)->after('session_id'));
	}

	public function down()
	{
		$this->dropColumn('{{%invite}}', 'status');
	}
}

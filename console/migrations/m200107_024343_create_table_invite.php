<?php

use yii\db\Migration;

class m200107_024343_create_table_invite extends Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			// http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		}

		$this->createTable('{{%invite}}', [
			'id' => $this->primaryKey(),
			'session_id' => $this->string()->notNull()->unique(),
			'sex' => $this->smallInteger(),
			'age' => $this->string(50),
			'family' => $this->smallInteger(),
			'children' => $this->smallInteger(),
			'repair_status' => $this->smallInteger(),
			'repair_when_finish' => $this->smallInteger(),
			'repair_object_other' => $this->string(200),
			'have_cottage' => $this->smallInteger(),
			'plan_cottage_works' => $this->smallInteger(),
			'who_worker' => $this->smallInteger(),
			'who_chooser' => $this->smallInteger(),
			'who_buyer' => $this->smallInteger(),
			'where_buy_other' => $this->string(200),
			'shop_name' => $this->string(200),
			'fio' => $this->string(200),
			'phone' => $this->string(200),
			'email' => $this->string(200),
			'distance' => $this->smallInteger(),
			'city_id' => $this->integer(),
			'city_other' => $this->string(255),
			'money' => $this->smallInteger(),
			'created_at' => $this->integer()->notNull(),
			'updated_at' => $this->integer()->notNull(),
		], $tableOptions);
	}

	public function down()
	{
		$this->dropTable('{{%invite}}');
	}
}

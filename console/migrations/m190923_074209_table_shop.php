<?php

use yii\db\Migration;

class m190923_074209_table_shop extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%shop}}', [
            'id' => $this->primaryKey(),
            'updated_at' => $this->integer(),
            'city_id' => $this->integer(),
            'number' => $this->string(3)->notNull()->unique(),
        ], $tableOptions);

        $this->createIndex('city_id', '{{%shop}}', ['city_id']);

    }

    public function down()
    {
        $this->dropTable('{{%shop}}');
    }

}

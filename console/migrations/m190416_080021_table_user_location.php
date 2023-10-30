<?php

use yii\db\Migration;

class m190416_080021_table_user_location extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user_location}}', [
            'id' => $this->primaryKey(),
            'journal_id' => $this->integer(),
            'user_id' => $this->integer(),
            'city_id' => $this->integer(),
            'is_home_adress' => $this->boolean()->notNull()->defaultValue(false),
            'adress' => $this->string(),
            'flat' => $this->string(),      //номер квартиры
            'latitude' => $this->float(),   //широта
            'longitude' => $this->float(),  //долгота
        ], $tableOptions);

        $this->createIndex('city_id', '{{%user_location}}', ['city_id']);
        $this->createIndex('journal_id', '{{%user_location}}', ['journal_id']);
        $this->createIndex('user_id', '{{%user_location}}', ['user_id']);
    }

    public function down()
    {
        $this->dropTable('{{%user_location}}');
    }
}

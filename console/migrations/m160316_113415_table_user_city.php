<?php

use yii\db\Schema;
use yii\db\Migration;

class m160316_113415_table_user_city extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user_city}}', [
            'user_id' => $this->integer(),
            'city_id' => $this->integer(),
        ], $tableOptions);

        $this->createIndex('user_id_city_id', '{{%user_city}}', ['user_id', 'city_id'], true);

        // Переносим данные
        $this->execute('INSERT INTO {{%user_city}} (user_id, city_id) SELECT id, city_id FROM {{%user}} WHERE city_id > 0');
    }

    public function down()
    {
        $this->dropTable('{{%user_city}}');
    }
}

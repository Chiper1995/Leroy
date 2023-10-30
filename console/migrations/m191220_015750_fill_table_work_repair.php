<?php

use yii\db\Migration;

class m191220_015750_fill_table_work_repair extends Migration
{

    public function up()
    {
        $this->insert('{{%work_repair}}', ['id' => 29, 'name' => 'Реставрация старой мебели', 'updated_at' => time()]);
    }

    public function down()
    {
        $this->delete('{{%work_repair}}', ['name'=>'Реставрация старой мебели']);
        return true;
    }
}

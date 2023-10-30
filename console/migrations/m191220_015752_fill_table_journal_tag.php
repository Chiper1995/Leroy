<?php

use yii\db\Migration;

class m191220_015752_fill_table_journal_tag extends Migration
{

    public function up()
    {
        $this->insert('{{%journal_tag}}', ['id' => 3, 'name' => 'Посещение мастер-классов, школы ремонта', 'updated_at' => time()]);
    }

    public function down()
    {
        $this->delete('{{%journal_tag}}', ['name'=>'Посещение мастер-классов, школы ремонта']);
        return true;
    }
}

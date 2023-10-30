<?php

use yii\db\Migration;

class m200326_105547_alter_journal_photo_table_change_description extends Migration
{
    public function up()
    {
        $this->execute('UPDATE {{%journal_photo}} SET description = NULL WHERE description = "undefined"');
    }

}

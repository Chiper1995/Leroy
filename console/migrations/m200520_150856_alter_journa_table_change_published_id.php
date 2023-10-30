<?php

use yii\db\Migration;

class m200520_150856_alter_journa_table_change_published_id extends Migration
{
    public function up()
    {
        $this->execute('UPDATE {{%journal}} SET published_id = -1 WHERE created_at < 1589760000');
    }

    public function down()
    {
        $this->execute('UPDATE {{%journal}} SET published_id = NULL WHERE created_at < 1589760000');
    }
}

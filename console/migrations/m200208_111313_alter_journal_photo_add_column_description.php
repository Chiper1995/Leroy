<?php

use yii\db\Migration;

class m200208_111313_alter_journal_photo_add_column_description extends Migration
{
    public function up()
    {
        $this->addColumn('{{%journal_photo}}', 'description', $this->string(400)->comment('Описание фотографии'));
    }

    public function down()
    {
        $this->dropColumn('{{%journal_photo}}', 'description');
    }
}

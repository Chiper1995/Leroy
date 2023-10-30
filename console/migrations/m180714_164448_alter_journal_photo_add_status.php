<?php

use yii\db\Migration;

class m180714_164448_alter_journal_photo_add_status extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%journal_photo}}', 'status', $this->smallInteger()->notNull()->defaultValue(0));

        $this->execute('
            UPDATE {{%journal_photo}} jp
                JOIN {{%journal}} j ON j.id = jp.journal_id
            SET jp.status = j.status
        ');
    }

    public function safeDown()
    {
        $this->dropColumn('{{%journal_photo}}', 'status');
    }
}

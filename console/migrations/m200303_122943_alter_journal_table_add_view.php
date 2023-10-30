<?php

use yii\db\Migration;

class m200303_122943_alter_journal_table_add_view extends Migration
{
    public function up()
    {
        $this->addColumn('{{%journal}}', 'view', $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Флаг просмотра записи'));
    }

    public function down()
    {
        $this->dropColumn('{{%journal}}', 'view');
    }
}

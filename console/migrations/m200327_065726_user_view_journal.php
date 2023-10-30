<?php

use yii\db\Migration;

class m200327_065726_user_view_journal extends Migration
{
    public function safeUp()
    {
        $this->dropColumn('{{%journal}}','view');
        $this->createTable('{{%user_view_journal}}', [
            'id' => $this->primaryKey(),
            'userId' => $this->integer()->notNull()->comment('Пользователь'),
            'journalId' => $this->integer()->notNull()->comment('Журнал'),
            'createdAt' => $this->dateTime()->notNull()->comment('Дата создания'),
            'updatedAt' => $this->dateTime()->comment('Дата изменения')
        ]);

        $this->addForeignKey('fk_user_view_journal_userId', '{{%user_view_journal}}', 'userId', '{{%user}}', 'id', 'CASCADE');
        $this->addForeignKey('fk_user_view_journal_journalId', '{{%user_view_journal}}', 'journalId', '{{%journal}}', 'id', 'CASCADE');
    }

    public function SafeDown()
    {
        $this->addColumn('{{%journal}}', 'view', $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('Флаг просмотра записи'));
        $this->dropForeignKey('fk_user_view_journal_userId', '{{%user_view_journal}}');
        $this->dropForeignKey('fk_user_view_journal_journalId', '{{%user_view_journal}}');

        $this->dropTable('{{%user_view_journal}}');
    }
}

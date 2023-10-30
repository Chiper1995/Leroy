<?php

use yii\db\Migration;

class m200226_100033_table_user_subscription_favorite extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%user_subscription_favorite}}', [
            'id' => $this->primaryKey(),
            'userId' => $this->integer()->notNull()->comment('Пользователь'),
            'journalId' => $this->integer()->notNull()->comment('Журнал'),
            'createdAt' => $this->dateTime()->notNull()->comment('Дата создания'),
            'updatedAt' => $this->dateTime()->comment('Дата изменения')
        ]);

        $this->addForeignKey('fk_user_subscription_favorite_userId', '{{%user_subscription_favorite}}', 'userId', '{{%user}}', 'id', 'CASCADE');
        $this->addForeignKey('fk_user_subscription_favorite_journalId', '{{%user_subscription_favorite}}', 'journalId', '{{%journal}}', 'id', 'CASCADE');
    }

    public function SafeDown()
    {
        $this->dropForeignKey('fk_user_subscription_favorite_userId', '{{%user_subscription_favorite}}');
        $this->dropForeignKey('fk_user_subscription_favorite_journalId', '{{%user_subscription_favorite}}');

        $this->dropTable('{{%user_subscription_favorite}}');
    }
}

<?php

use yii\db\Migration;

class m200422_074229_alter_journal_table_add_buy_list extends Migration
{
    public function safeup()
    {
        $this->addColumn('{{%journal}}', 'preparation_purchase', $this->getDb()->getSchema()->createColumnSchemaBuilder('LONGTEXT')->comment('запись подготовка к покупке'));
        $this->addColumn('{{%journal}}', 'store_selection', $this->getDb()->getSchema()->createColumnSchemaBuilder('LONGTEXT')->comment('запись выбор магазина'));
        $this->addColumn('{{%journal}}', 'assessment_product', $this->getDb()->getSchema()->createColumnSchemaBuilder('LONGTEXT')->comment('запись оценка выкладки товара'));
        $this->addColumn('{{%journal}}', 'conclusion', $this->getDb()->getSchema()->createColumnSchemaBuilder('LONGTEXT')->comment('запись заключение'));
        $this->addColumn('{{%journal}}', 'advice', $this->getDb()->getSchema()->createColumnSchemaBuilder('LONGTEXT')->comment('запись советы другим'));
        $this->addColumn('{{%journal}}', 'additional_information', $this->getDb()->getSchema()->createColumnSchemaBuilder('LONGTEXT')->comment('запись дополнительная информация'));
    }

    public function safedown()
    {
        $this->dropColumn('{{%journal}}', 'preparation_purchase');
        $this->dropColumn('{{%journal}}', 'store_selection');
        $this->dropColumn('{{%journal}}', 'assessment_product');
        $this->dropColumn('{{%journal}}', 'conclusion');
        $this->dropColumn('{{%journal}}', 'advice');
        $this->dropColumn('{{%journal}}', 'additional_information');
    }
}

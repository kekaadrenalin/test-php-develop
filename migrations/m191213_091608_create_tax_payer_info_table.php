<?php

use app\models\db\TaxOrgInfo;
use app\models\db\TaxPayerInfo;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%tax_payer_info}}`.
 */
class m191213_091608_create_tax_payer_info_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableName = TaxPayerInfo::tableName();

        $this->createTable('{{%tax_payer_info}}', [
            'id'              => $this->primaryKey(),
            'tax_org_info_id' => $this->integer()->notNull(),
            'nameRu'          => $this->string()->notNull(),
            'nameKk'          => $this->string()->notNull(),
            'iinBin'          => $this->string(12)->notNull(),
            'totalArrear'     => $this->decimal(12, 2)->defaultValue(0),
        ]);

        $this->addCommentOnColumn($tableName, 'tax_org_info_id', 'ID ОГД');
        $this->addCommentOnColumn($tableName, 'nameRu', 'Наименование налогоплательщика (рус)');
        $this->addCommentOnColumn($tableName, 'nameKk', 'Наименование налогоплательщика (каз)');
        $this->addCommentOnColumn($tableName, 'iinBin', 'ИИН/БИН налогоплательщика');
        $this->addCommentOnColumn($tableName, 'totalArrear', 'Всего задолженности (тенге)');

        $this->addCommentOnTable($tableName, 'Таблица задолженностей по налогоплательщику и его структурным подразделениям');

        $this->createIndex('idx-tax_payer_info-tax_org_info_id', $tableName, 'tax_org_info_id');

        $this->addForeignKey(
            'fk-tax_payer_info-tax_org_info_id',
            $tableName,
            'tax_org_info_id',
            TaxOrgInfo::tableName(),
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $tableName = TaxPayerInfo::tableName();

        $this->dropForeignKey('fk-tax_payer_info-tax_org_info_id', $tableName);
        $this->dropIndex('idx-tax_payer_info-tax_org_info_id', $tableName);

        $this->dropTable($tableName);
    }
}

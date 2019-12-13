<?php

use app\models\db\BccArrearsInfo;
use app\models\db\TaxPayerInfo;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%bcc_arrears_info}}`.
 */
class m191213_092430_create_bcc_arrears_info_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableName = BccArrearsInfo::tableName();

        $this->createTable($tableName, [
            'id'                => $this->primaryKey(),
            'tax_payer_info_id' => $this->integer()->notNull(),

            'bcc'       => $this->string()->notNull(),
            'bccNameRu' => $this->string()->notNull(),
            'bccNameKz' => $this->string()->notNull(),

            'taxArrear'     => $this->decimal(12, 2)->defaultValue(0),
            'poenaArrear'   => $this->decimal(12, 2)->defaultValue(0),
            'percentArrear' => $this->decimal(12, 2)->defaultValue(0),
            'fineArrear'    => $this->decimal(12, 2)->defaultValue(0),
            'totalArrear'   => $this->decimal(12, 2)->defaultValue(0),
        ]);

        $this->addCommentOnColumn($tableName, 'tax_payer_info_id', 'ID задолженности налогоплательщика');
        $this->addCommentOnColumn($tableName, 'bcc', 'КБК');
        $this->addCommentOnColumn($tableName, 'bccNameKz', 'Наименование задолженности (рус)');
        $this->addCommentOnColumn($tableName, 'bccNameKz', 'Наименование задолженности (каз)');
        $this->addCommentOnColumn($tableName, 'taxArrear', 'Задолженность по платежам, учет по которым ведется в органах государственных доходов');
        $this->addCommentOnColumn($tableName, 'poenaArrear', 'Задолженность по сумме пени');
        $this->addCommentOnColumn($tableName, 'percentArrear', 'Задолженность по сумме процентов');
        $this->addCommentOnColumn($tableName, 'fineArrear', 'Задолженность по сумме штрафа');
        $this->addCommentOnColumn($tableName, 'totalArrear', 'Всего задолженности');

        $this->addCommentOnTable($tableName, 'Таблица всех задолженностей по налогоплательщику');

        $this->createIndex('idx-bcc_arrears_info-tax_payer_info_id', $tableName, 'tax_payer_info_id');

        $this->addForeignKey(
            'fk-bcc_arrears_info-tax_payer_info_id',
            $tableName,
            'tax_payer_info_id',
            TaxPayerInfo::tableName(),
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $tableName = BccArrearsInfo::tableName();

        $this->dropForeignKey('fk-bcc_arrears_info-tax_payer_info_id', $tableName);
        $this->dropIndex('idx-bcc_arrears_info-tax_payer_info_id', $tableName);

        $this->dropTable($tableName);
    }
}

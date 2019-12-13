<?php

use app\models\db\Arrears;
use app\models\db\TaxOrgInfo;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%tax_org_info}}`.
 */
class m191213_085504_create_tax_org_info_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableName = TaxOrgInfo::tableName();

        $this->createTable($tableName, [
            'id'        => $this->primaryKey(),
            'arrear_id' => $this->integer()->notNull(),

            'nameRu'           => $this->string()->notNull(),
            'nameKk'           => $this->string()->notNull(),
            'charCode'         => $this->string(10)->notNull(),
            'reportAcrualDate' => $this->timestamp(),

            'totalArrear'                 => $this->decimal(12, 2)->defaultValue(0),
            'totalTaxArrear'              => $this->decimal(12, 2)->defaultValue(0),
            'pensionContributionArrear'   => $this->decimal(12, 2)->defaultValue(0),
            'socialContributionArrear'    => $this->decimal(12, 2)->defaultValue(0),
            'socialHealthInsuranceArrear' => $this->decimal(12, 2)->defaultValue(0),

            'appealledAmount'              => $this->decimal(12, 2)->defaultValue(0),
            'modifiedTermsAmount'          => $this->decimal(12, 2)->defaultValue(0),
            'rehabilitaionProcedureAmount' => $this->decimal(12, 2)->defaultValue(0),
        ]);

        $this->addCommentOnColumn($tableName, 'arrear_id', 'ID задолжности');
        $this->addCommentOnColumn($tableName, 'nameRu', 'Орган государственных доходов (рус)');
        $this->addCommentOnColumn($tableName, 'nameKk', 'Орган государственных доходов (каз)');
        $this->addCommentOnColumn($tableName, 'charCode', 'Код ОГД');
        $this->addCommentOnColumn($tableName, 'reportAcrualDate', 'Дата актуальности данных');
        $this->addCommentOnColumn($tableName, 'totalArrear', 'Всего задолженности (тенге)');
        $this->addCommentOnColumn($tableName, 'totalTaxArrear', 'Итого задолженности в бюджет');
        $this->addCommentOnColumn($tableName, 'pensionContributionArrear', 'Задолженность по обязательным пенсионным взносам, обязательным профессиональным пенсионным взносам');
        $this->addCommentOnColumn($tableName, 'socialContributionArrear', 'Задолженность по отчислениям и (или) взносам на обязательное социальное медицинское страхование');
        $this->addCommentOnColumn($tableName, 'socialHealthInsuranceArrear', 'Задолженность по социальным отчислениям');
        $this->addCommentOnColumn($tableName, 'appealledAmount', 'Суммы, начисленные по результатам налоговой проверки, находящиеся на стадии обжалования и обжалованные');
        $this->addCommentOnColumn($tableName, 'modifiedTermsAmount', 'Суммы, по которым изменены сроки уплаты');
        $this->addCommentOnColumn($tableName, 'rehabilitaionProcedureAmount', 'Суммы, по которым применена реабилитационная процедура');

        $this->addCommentOnTable($tableName, 'Таблица задолженностей по органам государственных доходов');

        $this->createIndex('idx-tax_org_info-arrear_id', $tableName, 'arrear_id');

        $this->addForeignKey(
            'fk-tax_org_info-arrear_id',
            $tableName,
            'arrear_id',
            Arrears::tableName(),
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $tableName = TaxOrgInfo::tableName();

        $this->dropForeignKey('fk-tax_org_info-arrear_id', $tableName);
        $this->dropIndex('idx-tax_org_info-arrear_id', $tableName);

        $this->dropTable($tableName);
    }
}

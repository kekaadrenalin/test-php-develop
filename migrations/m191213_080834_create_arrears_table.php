<?php

use app\models\db\Arrears;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%arrears}}`.
 */
class m191213_080834_create_arrears_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableName = Arrears::tableName();

        $this->createTable($tableName, [
            'id' => $this->primaryKey(),

            'user_id' => $this->integer()->notNull(),
            'iinBin'  => $this->string(12)->notNull(),

            'nameRu' => $this->string()->notNull(),
            'nameKk' => $this->string()->notNull(),

            'totalArrear'                 => $this->decimal(12, 2)->notNull()->defaultValue(0),
            'totalTaxArrear'              => $this->decimal(12, 2)->notNull()->defaultValue(0),
            'pensionContributionArrear'   => $this->decimal(12, 2)->notNull()->defaultValue(0),
            'socialContributionArrear'    => $this->decimal(12, 2)->notNull()->defaultValue(0),
            'socialHealthInsuranceArrear' => $this->decimal(12, 2)->notNull()->defaultValue(0),

            'appealledAmount'              => $this->decimal(12, 2)->notNull()->defaultValue(0),
            'modifiedTermsAmount'          => $this->decimal(12, 2)->notNull()->defaultValue(0),
            'rehabilitaionProcedureAmount' => $this->decimal(12, 2)->notNull()->defaultValue(0),

            'sendTime' => $this->timestamp()->notNull(),
        ]);

        $this->addCommentOnColumn($tableName, 'user_id', 'ID пользователя');
        $this->addCommentOnColumn($tableName, 'iinBin', 'ИИН/БИН налогоплательщика');
        $this->addCommentOnColumn($tableName, 'nameRu', 'Наименование налогоплательщика (рус)');
        $this->addCommentOnColumn($tableName, 'nameKk', 'Наименование налогоплательщика (каз)');
        $this->addCommentOnColumn($tableName, 'totalArrear', 'Всего задолженности (тенге)');
        $this->addCommentOnColumn($tableName, 'totalTaxArrear', 'Итого задолженности в бюджет');
        $this->addCommentOnColumn($tableName, 'pensionContributionArrear', 'Задолженность по обязательным пенсионным взносам, обязательным профессиональным пенсионным взносам');
        $this->addCommentOnColumn($tableName, 'socialContributionArrear', 'Задолженность по отчислениям и (или) взносам на обязательное социальное медицинское страхование');
        $this->addCommentOnColumn($tableName, 'socialHealthInsuranceArrear', 'Задолженность по социальным отчислениям');
        $this->addCommentOnColumn($tableName, 'appealledAmount', 'Суммы, начисленные по результатам налоговой проверки, находящиеся на стадии обжалования и обжалованные');
        $this->addCommentOnColumn($tableName, 'modifiedTermsAmount', 'Суммы, по которым изменены сроки уплаты');
        $this->addCommentOnColumn($tableName, 'rehabilitaionProcedureAmount', 'Суммы, по которым применена реабилитационная процедура');
        $this->addCommentOnColumn($tableName, 'sendTime', 'Дата запроса данных');

        $this->createIndex('idx-arrears-user_id', $tableName, 'user_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $tableName = Arrears::tableName();

        $this->dropIndex('idx-arrears-user_id', $tableName);

        $this->dropTable($tableName);
    }
}

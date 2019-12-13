<?php

namespace app\models\db;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%arrears}}".
 *
 * @property int          $id
 * @property int          $user_id ID пользователя
 * @property string       $iinBin ИИН/БИН налогоплательщика
 * @property string       $nameRu Наименование налогоплательщика (рус)
 * @property string       $nameKk Наименование налогоплательщика (каз)
 * @property float|null   $totalArrear Всего задолженности (тенге)
 * @property float|null   $totalTaxArrear Итого задолженности в бюджет
 * @property float|null   $pensionContributionArrear Задолженность по обязательным пенсионным взносам, обязательным профессиональным пенсионным взносам
 * @property float|null   $socialContributionArrear Задолженность по отчислениям и (или) взносам на обязательное социальное медицинское страхование
 * @property float|null   $socialHealthInsuranceArrear Задолженность по социальным отчислениям
 * @property float|null   $appealledAmount Суммы, начисленные по результатам налоговой проверки, находящиеся на стадии обжалования и обжалованные
 * @property float|null   $modifiedTermsAmount Суммы, по которым изменены сроки уплаты
 * @property float|null   $rehabilitaionProcedureAmount Суммы, по которым применена реабилитационная процедура
 * @property string       $sendTime Дата запроса данных
 *
 * @property TaxOrgInfo[] $taxOrgInfos
 */
class Arrears extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%arrears}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'iinBin', 'nameRu', 'nameKk', 'sendTime'], 'required'],
            [['user_id'], 'default', 'value' => null],
            [['user_id'], 'integer'],
            [['totalArrear', 'totalTaxArrear', 'pensionContributionArrear', 'socialContributionArrear', 'socialHealthInsuranceArrear', 'appealledAmount', 'modifiedTermsAmount', 'rehabilitaionProcedureAmount'], 'number'],
            [['sendTime'], 'safe'],
            [['iinBin'], 'string', 'max' => 12],
            [['nameRu', 'nameKk'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'                           => 'ID',
            'user_id'                      => 'ID пользователя',
            'iinBin'                       => 'ИИН/БИН налогоплательщика',
            'nameRu'                       => 'Наименование налогоплательщика (рус)',
            'nameKk'                       => 'Наименование налогоплательщика (каз)',
            'totalArrear'                  => 'Всего задолженности (тенге)',
            'totalTaxArrear'               => 'Итого задолженности в бюджет',
            'pensionContributionArrear'    => 'Задолженность по обязательным пенсионным взносам, обязательным профессиональным пенсионным взносам',
            'socialContributionArrear'     => 'Задолженность по отчислениям и (или) взносам на обязательное социальное медицинское страхование',
            'socialHealthInsuranceArrear'  => 'Задолженность по социальным отчислениям',
            'appealledAmount'              => 'Суммы, начисленные по результатам налоговой проверки, находящиеся на стадии обжалования и обжалованные',
            'modifiedTermsAmount'          => 'Суммы, по которым изменены сроки уплаты',
            'rehabilitaionProcedureAmount' => 'Суммы, по которым применена реабилитационная процедура',
            'sendTime'                     => 'Дата запроса данных',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getTaxOrgInfos()
    {
        return $this->hasMany(TaxOrgInfo::class, ['arrear_id' => 'id']);
    }
}

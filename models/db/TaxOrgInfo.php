<?php

namespace app\models\db;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%tax_org_info}}".
 *
 * @property int            $id
 * @property int            $arrear_id ID задолжности
 * @property string         $nameRu Орган государственных доходов (рус)
 * @property string         $nameKk Орган государственных доходов (каз)
 * @property string         $charCode Код ОГД
 * @property string|null    $reportAcrualDate Дата актуальности данных
 * @property float|null     $totalArrear Всего задолженности (тенге)
 * @property float|null     $totalTaxArrear Итого задолженности в бюджет
 * @property float|null     $pensionContributionArrear Задолженность по обязательным пенсионным взносам, обязательным профессиональным пенсионным взносам
 * @property float|null     $socialContributionArrear Задолженность по отчислениям и (или) взносам на обязательное социальное медицинское страхование
 * @property float|null     $socialHealthInsuranceArrear Задолженность по социальным отчислениям
 * @property float|null     $appealledAmount Суммы, начисленные по результатам налоговой проверки, находящиеся на стадии обжалования и обжалованные
 * @property float|null     $modifiedTermsAmount Суммы, по которым изменены сроки уплаты
 * @property float|null     $rehabilitaionProcedureAmount Суммы, по которым применена реабилитационная процедура
 *
 * @property Arrears        $arrear
 * @property TaxPayerInfo[] $taxPayerInfos
 */
class TaxOrgInfo extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%tax_org_info}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['arrear_id', 'nameRu', 'nameKk', 'charCode'], 'required'],
            [['arrear_id'], 'default', 'value' => null],
            [['arrear_id'], 'integer'],
            [['reportAcrualDate'], 'safe'],
            [['totalArrear', 'totalTaxArrear', 'pensionContributionArrear', 'socialContributionArrear', 'socialHealthInsuranceArrear', 'appealledAmount', 'modifiedTermsAmount', 'rehabilitaionProcedureAmount'], 'default', 'value' => 0],
            [['totalArrear', 'totalTaxArrear', 'pensionContributionArrear', 'socialContributionArrear', 'socialHealthInsuranceArrear', 'appealledAmount', 'modifiedTermsAmount', 'rehabilitaionProcedureAmount'], 'number'],
            [['nameRu', 'nameKk'], 'string', 'max' => 255],
            [['charCode'], 'string', 'max' => 10],
            [['arrear_id'], 'exist', 'skipOnError' => true, 'targetClass' => Arrears::class, 'targetAttribute' => ['arrear_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'                           => 'ID',
            'arrear_id'                    => 'ID задолжности',
            'nameRu'                       => 'Орган государственных доходов (рус)',
            'nameKk'                       => 'Орган государственных доходов (каз)',
            'charCode'                     => 'Код ОГД',
            'reportAcrualDate'             => 'Дата актуальности данных',
            'totalArrear'                  => 'Всего задолженности (тенге)',
            'totalTaxArrear'               => 'Итого задолженности в бюджет',
            'pensionContributionArrear'    => 'Задолженность по обязательным пенсионным взносам, обязательным профессиональным пенсионным взносам',
            'socialContributionArrear'     => 'Задолженность по отчислениям и (или) взносам на обязательное социальное медицинское страхование',
            'socialHealthInsuranceArrear'  => 'Задолженность по социальным отчислениям',
            'appealledAmount'              => 'Суммы, начисленные по результатам налоговой проверки, находящиеся на стадии обжалования и обжалованные',
            'modifiedTermsAmount'          => 'Суммы, по которым изменены сроки уплаты',
            'rehabilitaionProcedureAmount' => 'Суммы, по которым применена реабилитационная процедура',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getArrear()
    {
        return $this->hasOne(Arrears::class, ['id' => 'arrear_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getTaxPayerInfos()
    {
        return $this->hasMany(TaxPayerInfo::class, ['tax_org_info_id' => 'id']);
    }
}

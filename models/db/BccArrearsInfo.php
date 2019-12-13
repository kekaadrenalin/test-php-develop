<?php

namespace app\models\db;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%bcc_arrears_info}}".
 *
 * @property int          $id
 * @property int          $tax_payer_info_id ID задолженности налогоплательщика
 * @property string       $bcc КБК
 * @property string       $bccNameRu
 * @property string       $bccNameKz Наименование задолженности (каз)
 * @property float|null   $taxArrear Задолженность по платежам, учет по которым ведется в органах государственных доходов
 * @property float|null   $poenaArrear Задолженность по сумме пени
 * @property float|null   $percentArrear Задолженность по сумме процентов
 * @property float|null   $fineArrear Задолженность по сумме штрафа
 * @property float|null   $totalArrear Всего задолженности
 *
 * @property TaxPayerInfo $taxPayerInfo
 */
class BccArrearsInfo extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%bcc_arrears_info}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tax_payer_info_id', 'bcc', 'bccNameRu', 'bccNameKz'], 'required'],
            [['tax_payer_info_id'], 'default', 'value' => null],
            [['tax_payer_info_id'], 'integer'],
            [['taxArrear', 'poenaArrear', 'percentArrear', 'fineArrear', 'totalArrear'], 'default', 'value' => 0],
            [['taxArrear', 'poenaArrear', 'percentArrear', 'fineArrear', 'totalArrear'], 'number'],
            [['bcc', 'bccNameRu', 'bccNameKz'], 'string', 'max' => 255],
            [['tax_payer_info_id'], 'exist', 'skipOnError' => true, 'targetClass' => TaxPayerInfo::class, 'targetAttribute' => ['tax_payer_info_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'                => 'ID',
            'tax_payer_info_id' => 'ID задолженности налогоплательщика',
            'bcc'               => 'КБК',
            'bccNameRu'         => 'Bcc Name Ru',
            'bccNameKz'         => 'Наименование задолженности (каз)',
            'taxArrear'         => 'Задолженность по платежам, учет по которым ведется в органах государственных доходов',
            'poenaArrear'       => 'Задолженность по сумме пени',
            'percentArrear'     => 'Задолженность по сумме процентов',
            'fineArrear'        => 'Задолженность по сумме штрафа',
            'totalArrear'       => 'Всего задолженности',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getTaxPayerInfo()
    {
        return $this->hasOne(TaxPayerInfo::class, ['id' => 'tax_payer_info_id']);
    }
}

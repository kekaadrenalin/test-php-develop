<?php

namespace app\models\db;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%tax_payer_info}}".
 *
 * @property int              $id
 * @property int              $tax_org_info_id ID ОГД
 * @property string           $nameRu Наименование налогоплательщика (рус)
 * @property string           $nameKk Наименование налогоплательщика (каз)
 * @property string           $iinBin ИИН/БИН налогоплательщика
 * @property float|null       $totalArrear Всего задолженности (тенге)
 *
 * @property TaxOrgInfo       $taxOrgInfo
 * @property BccArrearsInfo[] $bccArrearsInfos
 */
class TaxPayerInfo extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%tax_payer_info}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tax_org_info_id', 'nameRu', 'nameKk', 'iinBin'], 'required'],
            [['tax_org_info_id'], 'default', 'value' => null],
            [['tax_org_info_id'], 'integer'],
            [['totalArrear'], 'default', 'value' => 0],
            [['totalArrear'], 'number'],
            [['nameRu', 'nameKk'], 'string', 'max' => 255],
            [['iinBin'], 'string', 'max' => 12],
            [['tax_org_info_id'], 'exist', 'skipOnError' => true, 'targetClass' => TaxOrgInfo::class, 'targetAttribute' => ['tax_org_info_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'              => 'ID',
            'tax_org_info_id' => 'ID ОГД',
            'nameRu'          => 'Наименование налогоплательщика (рус)',
            'nameKk'          => 'Наименование налогоплательщика (каз)',
            'iinBin'          => 'ИИН/БИН налогоплательщика',
            'totalArrear'     => 'Всего задолженности (тенге)',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getTaxOrgInfo()
    {
        return $this->hasOne(TaxOrgInfo::class, ['id' => 'tax_org_info_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getBccArrearsInfos()
    {
        return $this->hasMany(BccArrearsInfo::class, ['tax_payer_info_id' => 'id']);
    }
}

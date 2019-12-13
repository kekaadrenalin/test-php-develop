<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\db\Arrears;

/**
 * ArrearsSearch represents the model behind the search form of `app\models\db\Arrears`.
 */
class ArrearsSearch extends Arrears
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id'], 'integer'],
            [['iinBin', 'nameRu', 'nameKk', 'sendTime'], 'safe'],
            [['totalArrear', 'totalTaxArrear', 'pensionContributionArrear', 'socialContributionArrear', 'socialHealthInsuranceArrear', 'appealledAmount', 'modifiedTermsAmount', 'rehabilitaionProcedureAmount'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Arrears::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andWhere([
            'user_id' => Yii::$app->user->id,
        ]);

        $query->andFilterWhere([
            'id'                           => $this->id,
            'totalArrear'                  => $this->totalArrear,
            'totalTaxArrear'               => $this->totalTaxArrear,
            'pensionContributionArrear'    => $this->pensionContributionArrear,
            'socialContributionArrear'     => $this->socialContributionArrear,
            'socialHealthInsuranceArrear'  => $this->socialHealthInsuranceArrear,
            'appealledAmount'              => $this->appealledAmount,
            'modifiedTermsAmount'          => $this->modifiedTermsAmount,
            'rehabilitaionProcedureAmount' => $this->rehabilitaionProcedureAmount,
            //'sendTime'                     => $this->sendTime,
        ]);

        $query->andFilterWhere(['ilike', 'iinBin', $this->iinBin])
            ->andFilterWhere(['ilike', 'nameRu', $this->nameRu])
            ->andFilterWhere(['ilike', 'nameKk', $this->nameKk]);

        return $dataProvider;
    }
}

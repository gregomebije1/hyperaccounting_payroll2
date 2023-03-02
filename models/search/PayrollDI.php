<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PayrollDI as PayrollDIModel;

/**
 * PayrollDI represents the model behind the search form about `app\models\PayrollDI`.
 */
class PayrollDI extends PayrollDIModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'payroll_id', 'deductions_allowances_id', 'created_at', 'updated_at'], 'integer'],
            [['amount'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
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
        $query = PayrollDIModel::find();

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
        $query->andFilterWhere([
            'id' => $this->id,
            'payroll_id' => $this->payroll_id,
            'deductions_allowances_id' => $this->deductions_allowances_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'amount', $this->amount]);

        return $dataProvider;
    }
}

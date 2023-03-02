<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Employee as EmployeeModel;

/**
 * Employee represents the model behind the search form about `app\models\Employee`.
 */
class Employee extends Model
{
    /**
     * @inheritdoc
     */
    public $search;
    public function rules()
    {
        return [
            [['search'], 'string'],
            //[['search'], 'required'],
            //[['id', 'grade_level_id', 'department_id', 'bank_id', 'location_id', 'created_at', 'updated_at'], 'integer'],
            //[['lastname', 'firstname', 'middlename', 'bank_account_number', 'marital_status', 'date_of_birth', 'place_of_birth', 'nationality', 'passport_no', 'national_identity_card_no_or_residential_permit_no', 'local_govt_area_or_state_of_origin', 'name_of_next_of_kin_or_relationship', 'address_or_telephone_next_of_kin', 'permanent_home_or_family_address', 'abuja_residential_address', 'home_telephone_number', 'mobile_telephone_number', 'secondary_school_qualification', 'post_secondary_school_qualification', 'previous_employer', 'date_of_first_appointment', 'starting_position', 'penalities', 'passport', 'status'], 'safe'],
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
        //$query = EmployeeModel::find();

        // add conditions that should always apply here

	$employee = 'employee_' . Yii::$app->session['entity_id'];
	$branch = 'branch_' . Yii::$app->session['entity_id'];
	$location = 'location_' . Yii::$app->session['entity_id'];
	$bank = 'bank_' . Yii::$app->session['entity_id'];

	$query = (new \yii\db\Query())
	  ->select(["{$employee}.id", 'lastname', 'firstname', 'middlename',
		    "{$branch}.name AS branch_name", "{$location}.name AS location_name",
		    "{$bank}.name AS bank_name", 'status'])
	  ->from("{$employee}")
	  ->leftJoin("{$branch}", "{$branch}.id = {$employee}.branch_id")
	  ->leftJoin("{$location}", "{$location}.id = {$employee}.location_id")
	  ->leftJoin("{$bank}", "{$bank}.id = {$employee}.bank_id")
	  ->orderBy("{$employee}.id");
       
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->orFilterWhere(['like', 'lastname', $this->search])
            ->orFilterWhere(['like', 'firstname', $this->search])
            ->orFilterWhere(['like', 'middlename', $this->search])
	    ->orFilterWhere(['like', "{$branch}.name", $this->search])
	    ->orFilterWhere(['like', "{$location}.name", $this->search])
	    ->orFilterWhere(['like', "{$bank}.name", $this->search])
            ->orFilterWhere(['like', 'bank_account_number', $this->search])
            ->orFilterWhere(['like', 'marital_status', $this->search])
            ->orFilterWhere(['like', 'place_of_birth', $this->search])
            ->orFilterWhere(['like', 'nationality', $this->search])
            ->orFilterWhere(['like', 'passport_no', $this->search])
            ->orFilterWhere(['like', 'national_identity_card_no_or_residential_permit_no', $this->search])
            ->orFilterWhere(['like', 'local_govt_area_and_state_of_origin', $this->search])
            ->orFilterWhere(['like', 'name_of_next_of_kin_and_relationship', $this->search])
            ->orFilterWhere(['like', 'address_and_telephone_next_of_kin', $this->search])
            ->orFilterWhere(['like', 'permanent_home_or_family_address', $this->search])
            ->orFilterWhere(['like', 'abuja_residential_address', $this->search])
            ->orFilterWhere(['like', 'home_telephone_number', $this->search])
            ->orFilterWhere(['like', 'mobile_telephone_number', $this->search])
            ->orFilterWhere(['like', 'secondary_school_qualification', $this->search])
            ->orFilterWhere(['like', 'post_secondary_school_qualification', $this->search])
            ->orFilterWhere(['like', 'previous_employer', $this->search])
            ->orFilterWhere(['like', 'starting_position', $this->search])
            ->orFilterWhere(['like', 'penalities', $this->search])
            ->orFilterWhere(['like', 'passport', $this->search])
            ->orFilterWhere(['like', 'status', $this->search]);

        return $dataProvider;
    }
}

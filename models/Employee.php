<?php

namespace app\models;

use Yii;
use \yii\web\NotFoundHttpException;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "employee_1".
 *
 * @property integer $id
 * @property string $lastname
 * @property string $firstname
 * @property string $middlename
 * @property integer $grade_level_id
 * @property integer $department_id
 * @property integer $bank_id
 * @property string $bank_account_number
 * @property integer $branch_id
 * @property integer $location_id
 * @property string $marital_status
 * @property string $date_of_birth
 * @property string $place_of_birth
 * @property string $nationality
 * @property string $passport_no
 * @property string $national_identity_card_no_or_residential_permit_no
 * @property string $local_govt_area_and_state_of_origin
 * @property string $name_of_next_of_kin_and_relationship
 * @property string $address_and_telephone_next_of_kin
 * @property string $permanent_home_or_family_address
 * @property string $abuja_residential_address
 * @property string $home_telephone_number
 * @property string $mobile_telephone_number
 * @property string $secondary_school_qualification
 * @property string $post_secondary_school_qualification
 * @property string $previous_employer
 * @property string $date_of_first_appointment
 * @property string $starting_position
 * @property string $penalities
 * @property string $passport
 * @property string $status
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Bank1 $bank
 * @property Department1 $department
 * @property GradeLevel1 $gradeLevel
 * @property Location1 $location
 * @property EmployeeDeductionsAllowances1[] $employeeDeductionsAllowances1s
 * @property Payroll120121[] $payroll120121s
 
 */
class Employee extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'employee_' . Yii::$app->session['entity_id'];
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['lastname', 'firstname', 'grade_level_id', 'department_id', 'bank_id', 'location_id'], 'required'],
            [['grade_level_id', 'department_id', 'bank_id', 'branch_id', 'location_id', 'created_at', 'updated_at'], 'integer'],
            [['date_of_birth', 'date_of_first_appointment'], 'safe'],
            [['local_govt_area_and_state_of_origin', 'address_and_telephone_next_of_kin', 'permanent_home_or_family_address', 'abuja_residential_address', 'secondary_school_qualification', 'post_secondary_school_qualification', 'previous_employer', 'penalities'], 'string'],
            [['lastname', 'firstname', 'middlename', 'bank_account_number', 'marital_status', 'place_of_birth', 'nationality', 'passport_no', 'national_identity_card_no_or_residential_permit_no', 'name_of_next_of_kin_and_relationship', 'home_telephone_number', 'mobile_telephone_number', 'starting_position', 'passport', 'status'], 'string', 'max' => 100],
            [['bank_id'], 'exist', 'skipOnError' => true, 'targetClass' => Bank::className(), 'targetAttribute' => ['bank_id' => 'id']],
            [['branch_id'], 'exist', 'skipOnError' => true, 'targetClass' => Branch::className(), 'targetAttribute' => ['branch_id' => 'id']],
            [['department_id'], 'exist', 'skipOnError' => true, 'targetClass' => Department::className(), 'targetAttribute' => ['department_id' => 'id']],
            [['grade_level_id'], 'exist', 'skipOnError' => true, 'targetClass' => GradeLevel::className(), 'targetAttribute' => ['grade_level_id' => 'id']],
            [['location_id'], 'exist', 'skipOnError' => true, 'targetClass' => Location::className(), 'targetAttribute' => ['location_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'lastname' => 'Lastname',
            'firstname' => 'Firstname',
            'middlename' => 'Middlename',
            'grade_level_id' => 'Grade Level',
            'department_id' => 'Department',
            'bank_id' => 'Bank',
            'bank_account_number' => 'Bank Account Number',
            'branch_id' => 'Branch',
            'location_id' => 'Location',
            'marital_status' => 'Marital Status',
            'date_of_birth' => 'Date Of Birth',
            'place_of_birth' => 'Place Of Birth',
            'nationality' => 'Nationality',
            'passport_no' => 'Passport No',
            'national_identity_card_no_or_residential_permit_no' => 'National Identity Card No Or Residential Permit No',
            'local_govt_area_and_state_of_origin' => 'Local Govt Area And State Of Origin',
            'name_of_next_of_kin_and_relationship' => 'Name Of Next Of Kin And Relationship',
            'address_and_telephone_next_of_kin' => 'Address And Telephone Next Of Kin',
            'permanent_home_or_family_address' => 'Permanent Home Or Family Address',
            'abuja_residential_address' => 'Abuja Residential Address',
            'home_telephone_number' => 'Home Telephone Number',
            'mobile_telephone_number' => 'Mobile Telephone Number',
            'secondary_school_qualification' => 'Secondary School Qualification',
            'post_secondary_school_qualification' => 'Post Secondary School Qualification',
            'previous_employer' => 'Previous Employer',
            'date_of_first_appointment' => 'Date Of First Appointment',
            'starting_position' => 'Starting Position',
            'penalities' => 'Penalities',
            'passport' => 'Passport',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBank()
    {
        return $this->hasOne(Bank::className(), ['id' => 'bank_id']);
    }

    /** 
     * @return \yii\db\ActiveQuery 
     */ 
    public function getBranch() 
    { 
        return $this->hasOne(Branch::className(), ['id' => 'branch_id']);
    } 
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDepartment()
    {
        return $this->hasOne(Department::className(), ['id' => 'department_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGradeLevel()
    {
        return $this->hasOne(GradeLevel::className(), ['id' => 'grade_level_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocation()
    {
        return $this->hasOne(Location::className(), ['id' => 'location_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployeeDeductionsAllowances()
    {
        return $this->hasMany(EmployeeDeductionsAllowances::className(), ['employee_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayrolls()
    {
        return $this->hasMany(Payroll::className(), ['employee_id' => 'id']);
    }
    
    public function getPayroll($date)
    {
        return Payroll::findOne(['employee_id' => $this->id, 'payroll_date' => $date]);
    }
    public function getPayrollByLocation($date, $location_id)
    {
        return Payroll::findOne(['employee_id' => $this->id, 
            'payroll_date' => $date, 'location_id' => $location_id]);
    }
    public function isDeductionOrAllowance($id) {
      $da = 'deductions_allowances_' . Yii::$app->session['entity_id'];
      $sql ="select COUNT(*) from {$da} where id=:id 
            and (name='PAYE' or name='HOUSING' or name='UTILITY' or name='MEAL' 
                    or name='ENTERTAINMENT' or name='TRANSPORT')";
      $params = [':id' => $id];
      $count = Yii::$app->db->createCommand($sql)
            ->bindValues($params)
            ->queryScalar();
      return $count == 0 ? false : true;
    }

    public function getPayeeItems($group) {
      $payee_item = 'payee_item_' . Yii::$app->session['entity_id'];
      $sql="select * from {$payee_item} where payee_item_group=:group";
      $params = [':group' => $group];
      return Yii::$app->db->createCommand($sql)
            ->bindValues($params)
            ->queryAll();
    }

    public function getBenefitsInKind() {
      $da = 'deductions_allowances_' . Yii::$app->session['entity_id'];
      $eda = 'employee_deductions_allowances_' . Yii::$app->session['entity_id'];
      
      $sql = "select d.name, edi.amount from {$eda} edi 
          join {$da} d on edi.deductions_allowances_id = d.id 
          where edi.employee_id=:employee_id and
            (d.name = 'Staff Accommodation' or d.name='Dressing Allowance' 
             or d.name = 'Recharge Card Allowance')";
      $params = [':employee_id' => $this->id];
      return Yii::$app->db->createCommand($sql)
            ->bindValues($params)
            ->queryAll();
    }
    
    public function getLeaveAllowance() {
       $da = 'deductions_allowances_' . Yii::$app->session['entity_id'];
       $eda = 'employee_deductions_allowances_' . Yii::$app->session['entity_id'];
      
        $sql = "select edi.amount from {$eda} edi join 
          {$da} d on edi.deductions_allowances_id = d.id 
          where d.name = 'Leave Allowance' and edi.employee_id=:employee_id";
        $params = [':employee_id' => $this->id];
        return Yii::$app->db->createCommand($sql)
            ->bindValues($params)
            ->queryScalar();
    }
    
    public function getSalary() {
        $employee = 'employee_' . Yii::$app->session['entity_id'];
        $grade_level = 'grade_level_' . Yii::$app->session['entity_id'];
                   
        $sql ="select gl.basic_salary from {$employee} e 
          join {$grade_level} gl on e.grade_level_id = gl.id 
          where e.id=:employee_id";
        $params = [':employee_id' => $this->id];
        $amount = Yii::$app->db->createCommand($sql)
            ->bindValues($params)
            ->queryScalar();
        return $amount;
    }
    
    public function calculatePayeBasic($basic_name) { 
        $amount = 0;
        
        if($basic_name == 'PAYE') {
          $amount = $this->calculatePaye();
        } else {
            $salary = $this->getSalary();
            $payee_items = $this->getPayeeItems('paye');
            foreach($payee_items as $payee_item) {
                $temp = $payee_item["payee_item_type"] == 'percentage' 
                        ? $payee_item["amount"]/100 : $payee_item["amount"];
                if ($payee_item["name"] == $basic_name) {
                    $amount = $salary * $temp;
                    break;
                }
            }
        }
        return $amount;
    }

    public function calculatePaye() {

      $salary = $this->getSalary();
      $annual_salary = $salary * 12;

      $allowance = $this->getLeaveAllowance(); 
      if ($allowance == null)
        $allowance = 0;
      $annual_allowance = $allowance * 12;
      
      $payee_items = $this->getPayeeItems('paye');
      $benefits_in_kind = $this->getBenefitsInKind();

      $relief_items = $this->getPayeeItems('reliefs');
      $exemptions_items = $this->getPayeeItems('exemptions');

      $total_benefits = 0;
      $total_payee = 0;
      $total_reliefs_exemptions = 0;
      $basic = 0;
      $housing = 0;
      $transport  = 0;
      $paye_per_annum = 0;


      foreach($payee_items as $payee_item) {
        $temp = $payee_item['payee_item_type'] == 'percentage' 
                ? $payee_item['amount']/100 : $payee_item['amount'];
        if ($payee_item['name'] == 'BASIC')
          $basic = $annual_salary * $temp;
        else if ($payee_item['name'] == 'HOUSING')
          $housing = $annual_salary * $temp;
        else if ($payee_item['name'] == 'TRANSPORT')
          $transport = $annual_salary * $temp;
        
        $total_payee += ($annual_salary * $temp); 
      }

      foreach($benefits_in_kind as $benefit) {
        $total_benefits += $benefit['amount'];
      }
      $gross_income = $total_payee + $annual_allowance + $total_benefits;

      foreach($relief_items as $relief_item) {
        if ($relief_item['name'] == 'PERSONAL ALLOWANCE')
            $temp = ($gross_income * ($relief_item['amount']/100)) + 200000;
        else 
            $temp = $relief_item['payee_item_type'] == 'percentage' 
                ? $relief_item['amount']/100 : $relief_item['amount'];
        $total_reliefs_exemptions += $temp;
      }

      foreach($exemptions_items as $exemption_item) {
        if ($exemption_item['name'] == 'NHF') 
               $temp = ($exemption_item['amount']/100) * $basic;
        else if ($exemption_item['name'] == 'PENSION') 
            $temp = ($exemption_item['amount']/100) * ($basic + $housing + $transport);
        else 
          $temp = $exemption_item['payee_item_type'] == 'percentage' 
                ? $exemption_item['amount']/100 : $exemption_item['amount'];

        $total_reliefs_exemptions += $temp;
      }
      $taxable_income = $gross_income - $total_reliefs_exemptions;

      //If taxable income is negative use 1%
       if ($taxable_income <= 0)
         $annual_paye = (1/100) * $taxable_income;

       $arr = array(1 => array('7','300000'), 
                    2 => array('11', '300000'), 
                    3 => array('15', '500000',),
                    4 => array('19', '500000',),
                    5 => array('21', '1600000',),
                    6 => array('24', '3200000'));
       $bal = 0;
       foreach($arr as $key => $value) {
         if ($value[0] == '7') {
               $bal = $taxable_income;
         }   
         $bal = $bal - $value[1];
         if (($key + 1) <= count($arr))
           $next_bal = $bal - $arr[$key + 1][1]; //Next balance
         else
           $next_bal = 0;

         if (($value[0] == 7) && ($bal <= 0)) {
           $paye_per_annum += ($taxable_income * ($value[0]/100));
               $bal = 0;
         } else if ($next_bal <= 0) {
           $paye_per_annum += (($bal + $value[1])* ($value[0]/100));
               $bal = 0;
         } else {
           $paye_per_annum += ($value[1] * ($value[0]/100));
         }
       }
       if (($salary >= 24500) && ($salary < 30000))
         return (1/100) * $salary;
       else if ($paye_per_annum < 0)
         return (1/100) * $salary;
       else
         return $paye_per_annum/12;

       //abs($paye_per_annum/12);
       //Effective Tax Rate: $paye_per_annum/$gross_income)*(100/1)
    }
}

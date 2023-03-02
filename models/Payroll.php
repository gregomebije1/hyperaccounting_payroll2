<?php

namespace app\models;

use Yii;
use yii\web\NotFoundHttpException;
use common\models\LoginForm;
use yii\behaviors\TimestampBehavior;
use app\models\DeductionsAllowances;
use app\models\EmployeeDeductionsAllowances;
/**
 * This is the model class for table "payroll_1_2012_1".
 *
 * @property integer $id
 * @property integer $employee_id
 * @property string $payroll_date
 * @property string $basic_salary
 * @property integer $location_id
 * @property integer $branch_id
 * @property integer $branch_id
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Employee1 $employee
 * @property PayrollDi120121[] $payrollDi120121s
 */
class Payroll extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'payroll_' .  Yii::$app->session['entity_id'] . '_'
                . Yii::$app->session['year_id'] . '_'  . Yii::$app->session['month_id'];
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
            [['employee_id', 'payroll_date', 'basic_salary',  'location_id', 'branch_id'], 'required'],
            [['employee_id', 'created_at', 'updated_at',  'location_id', 'branch_id'], 'integer'],
            [['payroll_date'], 'safe'],
            [['basic_salary'], 'string', 'max' => 100],
            [['employee_id'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::className(), 'targetAttribute' => ['employee_id' => 'id']],
            [['branch_id'], 'exist', 'skipOnError' => true, 'targetClass' => Branch::className(), 'targetAttribute' => ['branch_id' => 'id']],
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
            'employee_id' => 'Employee ID',
            'payroll_date' => 'Payroll Date',
            'basic_salary' => 'Basic Salary',
            'location_id' => 'Location ID',
            'branch_id' => 'Branch ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
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
    public function getEmployee($bank_id)
    {
        if ($bank_id == 0)
          return Employee::findOne(['id' => $this->employee_id]);
        else 
          return Employee::findOne(['id' => $this->employee_id, 'bank_id' => $bank_id]);
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
    /*
    public function getPayrollDis() 
    { 
        return $this->hasMany(PayrollDi::className(), ['payroll_id' => 'id']);
    } 
    */
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayrollDis($ded_all_id)
    {
        //return $this->hasMany(PayrollDi::className(), ['payroll_id' => 'id']);
        return PayrollDi::findOne(['payroll_id' => $this->id, 'deductions_allowances_id' => $ded_all_id]);
    }
   
    public static function processPayroll($date, $branch_id, $location_id, $employee_id) 
    {
        
        $employeeTable = "employee_" . Yii::$app->session['entity_id'];
        $locationTable = "location_" . Yii::$app->session['entity_id'];
        $payrollTable = 'payroll_'  . Yii::$app->session['entity_id'] . "_" . Yii::$app->session['year_id'] . "_" . Yii::$app->session['month_id'];
        $payrollDiTable = 'payroll_di_' . Yii::$app->session['entity_id'] . "_" . Yii::$app->session['year_id'] . "_" . Yii::$app->session['month_id'];
                  
        //Determine which branch to process
        if ($branch_id == '0') { //User chose 'All'
          $branchSql = "branch_id != 0";
          $branchName = "All"; 
        } else {
          $branchSql = "branch_id = {$branch_id}";
          $branchName = Branch::find()->where(['id' => $branch_id])->one();
        } 
        //Determine which location to process  
        if ($location_id == '0')  {  //User chose 'All'
            $locationSql = " location_id != 0";
            $locationSql2 = " {$locationTable}.id != 0";
            $locationName = "All"; 
        } else {
            $locationSql = " location_id = {$location_id}";
            $locationSql2 = " {$locationTable}.id = {$location_id} ";
            $locationName = Location::find()->where(['id' => $location_id])->one();
        }

        //Determine which employee to process  
        if ($employee_id == '0')  {  //User chose 'All'
            $employeeSql = " {$employeeTable}.id != 0";
            $employeeSql2 = " id != 0";
        } else {
            $employeeSql = " {$employeeTable}.id = {$employee_id}";
            $employeeSql2 = " id = {$employee_id}";
        }

        //Determine which employee to process 
        $employees = Employee::find()
            ->select("{$employeeTable}.*")
            ->innerJoin($locationTable, "{$employeeTable}.location_id = {$locationTable}.id")
            ->where(['status' => 'Enable'])
            ->andWhere($locationSql2)
            ->andWhere("{$locationTable}.branch_id = {$branch_id}")
            ->andWhere($employeeSql)
            ->all();
            
        $output = [];  
        $transaction = Yii::$app->db->beginTransaction();
        try {
          $sql = "DELETE FROM {$payrollDiTable} WHERE payroll_id IN (
                    SELECT id FROM {$payrollTable} where employee_id
                     IN (SELECT {$employeeTable}.id
                            FROM {$employeeTable}
                            WHERE $employeeSql2
                            ORDER BY id
                        )
                      AND payroll_date = :date AND {$locationSql} AND {$branchSql})";
            $params = [':date' => $date];
            Yii::$app->db->createCommand($sql)
                    ->bindValues($params)
                    ->execute();         
        
            $sql = "DELETE FROM {$payrollTable} WHERE employee_id IN
                    (   SELECT {$employeeTable}.id
                            FROM {$employeeTable}
                            WHERE $employeeSql2
                            ORDER BY id
                    )
                    AND payroll_date = :date AND {$locationSql} AND {$branchSql}";
            Yii::$app->db->createCommand($sql)
                    ->bindValues($params)
                    ->execute();
            
            $payroll_di_sql = "insert into {$payrollDiTable}(payroll_id,
             deductions_allowances_id, amount, created_at, updated_at) values ";
            $found = false;
            foreach($employees as $employee) {
                $basic_salary = $employee->calculatePayeBasic('BASIC');
                
                $payroll = new Payroll([
                    'employee_id'   => $employee->id,
                    'payroll_date'  => $date, 
                    'basic_salary'  => strval($basic_salary), 
                    'location_id'   => $employee->location_id,
                    'branch_id'     => $branch_id,
                    'created_at'    => time(),
                    'updated_at'    => time()
                ]);
                /*
                if (!$payroll->save()) {
                    throw new NotFoundHttpException(var_dump($payroll->errors));
                    
                }
                */
                if ($payroll->save()) {
                    $output[] = ["{$employee->firstname} {$employee->lastname}", $basic_salary];
                    $sql="SELECT di.id, di.name, edi.amount FROM employee_deductions_allowances_1 edi 
                            JOIN deductions_allowances_1 di ON edi.deductions_allowances_id = di.id WHERE employee_id=:employee_id";
                    $payroll_dis = Yii::$app->db->createCommand($sql)
                        ->bindValues([':employee_id' => $employee->id])
                        ->queryAll();

                    $payrollCount = 1;
                    foreach ($payroll_dis as $payroll_di)
                    {
                        if ($payroll_di['name'] == 'PAYE')
                            $value = $employee->calculatePayeBasic('PAYE');
                        else if ($payroll_di['name'] == 'HOUSING')
                            $value = $employee->calculatePayeBasic('HOUSING');
                        else if ($payroll_di['name'] == 'TRANSPORT')
                            $value = $employee->calculatePayeBasic('TRANSPORT');
                        else if ($payroll_di['name'] == 'UTILITY')
                            $value = $employee->calculatePayeBasic('UTILITY');
                        else if ($payroll_di['name'] == 'MEAL')
                            $value = $employee->calculatePayeBasic('MEAL');
                        else if ($payroll_di['name'] == 'ENTERTAINMENT')
                            $value = $employee->calculatePayeBasic('ENTERTAINMENT');
                        else 
                            $value = $payroll_di['amount'];

                        $timestamp =  time(); 
                        $payroll_di_sql .= "($payroll->id, {$payroll_di['id']}, '{$value}', '{$timestamp}', '{$timestamp}'),";
                        
                        $found = true;
                    }
                }
            }
            $payroll_di_sql = substr($payroll_di_sql, 0, -1);
            if ($found) {
                Yii::$app->db->createCommand($payroll_di_sql)
                    ->execute();
            
                $transaction->commit();
            }
        } catch(\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
        return $output; 
    }
    
    /**
     * 
     * @param string $date
     * @param type $branch_id
     * @param type $location_id
     * @param type $bank_id
     * @return string
     */
     public static function processUploadedPayroll($date, $branch_id, $location_id, $employee_id, 
             $basic_salary, $data) 
    {
        $employeeTable = "employee_" . Yii::$app->session['entity_id'];
        $locationTable = "location_" . Yii::$app->session['entity_id'];
        $payrollTable = 'payroll_'  . Yii::$app->session['entity_id'] . "_" . Yii::$app->session['year_id'] . "_" . Yii::$app->session['month_id'];
        $payrollDiTable = 'payroll_di_' . Yii::$app->session['entity_id'] . "_" . Yii::$app->session['year_id'] . "_" . Yii::$app->session['month_id'];
                  
        $branchSql = "branch_id = {$branch_id}";
        $locationSql = " location_id = {$location_id}";

        $output = array();  
        $count = 1;
        $transaction = Yii::$app->db->beginTransaction();
        try {
         
            $sql = "DELETE FROM  {$payrollDiTable} WHERE payroll_id IN 
              (SELECT id FROM {$payrollTable} WHERE employee_id = {$employee_id}
                        AND payroll_date = :date AND {$locationSql} AND {$branchSql})";
            $params = [':date' => $date];
            Yii::$app->db->createCommand($sql)
                    ->bindValues($params)
                    ->execute();         
        
            $sql = "DELETE FROM {$payrollTable} WHERE employee_id = {$employee_id}
                        AND payroll_date = :date AND {$locationSql} AND {$branchSql}";
            $params = [':date' => $date];
            Yii::$app->db->createCommand($sql)
                    ->bindValues($params)
                    ->execute();
            
            //Determine which employee to process 
            $employee = Employee::find()
            ->select("{$employeeTable}.*")
            ->where(['id' => $employee_id])
            ->one();

            $payroll_di_sql = "";
            if ($employee != NULL) 
            {
                $payroll_di_sql = "insert into {$payrollDiTable}(payroll_id,
                 deductions_allowances_id, amount, created_at, updated_at) values ";
                $payroll = new Payroll([
                        'employee_id'   => $employee->id,
                        'payroll_date'  => $date, 
                        'basic_salary'  => strval($basic_salary), 
                        'location_id'   => $location_id,
                        'branch_id'     => $branch_id,
                        'created_at'    => time(),
                        'updated_at'    => time()
                ]);
                if (!$payroll->save()) {
                    throw new NotFoundHttpException(print_r($payroll->errors, true) . "{$employee->firstname} {$employee->lastname}");
                    
                }
                $payrollCount = 1;
                array_push($output, "{$employee->firstname} {$employee->lastname}");
                array_push($output, $basic_salary);
                
                foreach ($data as $id => $value)
                {
                    $timestamp =  time(); 
                    $payroll_di_sql .= "($payroll->id, {$value[0]}, '{$value[1]}', '{$timestamp}', '{$timestamp}')";
                    if (count($data) != $payrollCount) 
                        $payroll_di_sql .= ", ";

                    $payrollCount += 1;
                    array_push($output, $value[1]);
                }                          
            }
            Yii::$app->db->createCommand($payroll_di_sql)
                    ->bindValues($params)
                    ->execute();
            
            $transaction->commit();

                
        } catch(\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
        return $output; 
    }
    
    public static function getPayrollReport($date, $branch_id, $location_id, $bank_id) 
    {
        $date = Yii::$app->session['year_id'] . "-" . Yii::$app->session['month_id'] . "-01";
       
        $bankTable = "bank_" . Yii::$app->session['entity_id'];
        $locationTable = "location_" . Yii::$app->session['entity_id'];
        $employeeTable = "employee_" . Yii::$app->session['entity_id'];
        $payrollTable = "payroll_" . Yii::$app->session['entity_id'];
        
        $deductions = DeductionsAllowances::findAll(['type' => 'Deductions']);
        $allowances = DeductionsAllowances::findAll(['type' => 'Allowances']);
                                
        
        //Determine with location(s) to process
        if ($location_id != 0) {
            $locations = Location::findAll($location_id);

        } else {
            $locations = Location::findAll(['branch_id' => $branch_id]);
        }  
       

        $banks = ($bank_id != 0) ? Bank::findOne($bank_id) : Bank::find()->all();            
        $branch = Branch::findOne($branch_id); 

        $total = ['basic_salary' => 0];
        
        foreach ($allowances as $allowance) {
            $name = "{$allowance->id}_Allowances";
            $total[$name] = 0;
        }
        $total['gross_salary'] = 0;

        foreach ($deductions as $deduction) {
            $name = "{$deduction->id}_Deductions";
            $total[$name] = 0;
        }
        $total['net_pay'] = 0;
            
        $output = [];
        $count = 1;
        
        
        foreach ($locations as $location) {
            $payrolls = $location->getPayroll($date);

            if (count($payrolls) > 0) {           
                $loc = [$branch->name];
                array_push($loc, $location->name); 
                array_push($loc, "-"); 
                
                //$loc[1] = "-";
                $output[] = $loc;
            
                foreach ($payrolls as $payroll)
                {
                    $arr = [];
                    $total_allowances = 0;
                    $total_deductions = 0;
            
                    $employee = $payroll->getEmployee($bank_id); 
                    if (($employee == null) || ($employee->status == "Disable")) 
                        continue;
                    array_push($arr, $count);
                    array_push($arr, $employee->id);
                    array_push($arr, "{$employee->firstname} {$employee->lastname}");
                    array_push($arr, (strlen($employee->bank_account_number) != 0) ? $employee->bank_account_number : "");
                    array_push($arr, $payroll->basic_salary);
                    $total["basic_salary"] += $payroll->basic_salary;
                    
                    foreach ($allowances as $allowance) {
                        $payroll_di = $payroll->getPayrollDis($allowance->id);
                        $amount = 0;
                        if ($payroll_di !== null) {
                            $amount = !is_numeric($payroll_di->amount) ? 0 : $payroll_di->amount;
                            $total["{$allowance->id}_Allowances"] += $amount;
                            $total_allowances += $amount;
                        }                   
                        //array_push($arr, is_string($amount) ? number_format(doubleval($amount), 2) : number_format($amount, 2));
                        array_push($arr, $amount);
                    }
                    $gross_salary = $payroll->basic_salary + $total_allowances;
                    //array_push($arr, number_format($gross_salary, 2));
                    array_push($arr, $gross_salary);
                    $total['gross_salary'] += $gross_salary;
                    
                    foreach ($deductions as $deduction) {
                        $payroll_di = $payroll->getPayrollDis($deduction->id);

                        $amount = 0;
                        if ($payroll_di !== null) {
                        $amount =  !is_numeric($payroll_di->amount) ? 0 : $payroll_di->amount;
                        $total["{$deduction->id}_Deductions"] += $amount;
                        $total_deductions += $amount;
                        }
                        //array_push($arr, is_string($amount) ? number_format(doubleval($amount), 2) : number_format($amount, 2));
                        array_push($arr, $amount);
                    }
                    
                    $net_pay = $gross_salary - $total_deductions;
                    //array_push($arr, number_format($net_pay, 2));
                    array_push($arr, $net_pay);
                    $total['net_pay'] += $net_pay;
                    
                    $output[] = $arr;
                    $count += 1;
                    
                }
            }
        }
        if (count($output) > 0) {                    
            $total2 = ["TOTAL", "", "", ""];
            foreach($total as $name => $value) {
                //array_push($total2, number_format($value, 2));
                array_push($total2, $value);
            } 
            $output[] = $total2;
        }
        return $output;
    }
    public static function uploadPayroll($filename, $date, $branch_id)
    {
        $output = []; 
        $ded_allow = [];
        $location = null;
        
        if (($handle = fopen($filename, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if (trim($data[0]) == "S/N") {
                   for($i = 3; $i < count($data); $i++)
                   {
                       $name = trim(ucwords(strtolower($data[$i])));
                       if (trim($data[$i]) == "GROSS SALARY") 
                           continue;
                       else if (trim($data[$i]) == "TOTAL NET SALARY")
                           break;
                       else {
                        $dedAllow = DeductionsAllowances::find()->where(['name' => $name])->one();
                        if ($dedAllow != null)
                             array_push($ded_allow, [$dedAllow->id, 0]);
                        else
                            throw new NotFoundHttpException("Deduction allowance not found {$name} {$i}");
                       }
                   }
                }
                else if (strlen($data[1]) == 0) {
                    $location = Location::findOne(['name' => trim($data[0]), 'branch_id' => $branch_id]);
                    if ($location == NULL) {
                        //throw new NotFoundHttpException("Location not found "  . trim($data[0]));
                        $location = new Location();
                        $location->name = trim($data[0]);
                        $location->branch_id = $branch_id;
                        $location->save();
                    }
                }
                else {
                    $basic_salary = $data[2];
                    $name = explode(' ', trim($data[1]));
                    if (count($name) < 2)
                        continue;
                    
                    $employee = Employee::findOne(['firstname' => $name[0], 
                        'lastname' => $name[count($name) - 1]]);
                    if ($employee == NULL) {
                        //throw new NotFoundHttpException("Employee not found {$name[0]} {$name[1]}");
                        $employee = new Employee();
                        $employee->lastname = $name[count($name) - 1];
                        $employee->firstname = $name[0];
                        $employee->grade_level_id = 1;
                        $employee->department_id = 8;
                        $employee->bank_id = 1;
                        $employee->branch_id = $branch_id;
                        $employee->location_id = $location->id;
                        $employee->status = 'Enable';
                        $employee->save();
                    }
                  
                    $i = 3;
                    foreach($ded_allow as $index => $value) {
                        if ($i >= 14) 
                            $ded_allow[$index][1] = $data[$i + 1];
                        else 
                            $ded_allow[$index][1] = $data[$i];
                        $i += 1;
                    }
                    $output[] = Payroll::processUploadedPayroll($date, $branch_id, $location->id, $employee->id, $basic_salary, $ded_allow);
                }  
            }
            fclose($handle);
        }
        return $output;
    }
    
    public static function createEmployee($employee) {
      $transaction = Yii::$app->db->beginTransaction();
      try {
        $employee->status = "Enable";
        if(!$employee->save())
	  throw new HttpNotFoundException(print_r($employee->errors, true));

        $dedAlls = DeductionsAllowances::find()->all();
        foreach ($dedAlls as $dedAll) {
          $empDedAll = new EmployeeDeductionsAllowances();
          $empDedAll->attributes = [
            'employee_id' => $employee->id,
            'deductions_allowances_id' => $dedAll->id,
	    'amount' => '0'
          ];
          if(!$empDedAll->save())
	    throw new NotFoundHttpException(print_r($empDedAll->errors, true));
        }
        $transaction->commit();     
      } catch (\Exception $e) {
        $transaction->rollBack();
        throw $e;
      }
      return $employee->id;
    }
}

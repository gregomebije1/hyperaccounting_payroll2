<?php
namespace unit\tests;

use Yii;
use app\models\User;
use app\models\Employee;
use app\models\LoginForm;
use app\models\Payroll;

class PayrollTest extends \Codeception\Test\Unit
{
    /**
     * @var \app\tests\UnitTester
     */
    protected $tester;
    
    protected function _before()
    {
        $model = new LoginForm([
            'username' => 'admin@gmail.com',
            'password' => 'password'
        ]);        
        $this->assertTrue($model->login(), 'model should login user');
        
        //Set entity_id session variable
        $user = User::findByUsername($model->username);
        Yii::$app->session['entity_id'] = $user->entity_id;
        
    }

    protected function _after()
    {
    }

    public function testEmployeeCreated()
    {  
      $model = new Employee();
      $model->attributes = [
            'firstname' => 'Greg',
            'lastname' => 'Omebije',
            'grade_level_id' => 12,
            'department_id' => 6,
            'bank_id' => 10,
            'location_id' => 368
      ];
      //$this->assertTrue($model->save());
      //$this->assertNotNull($model->errors, 'error message should not be set');
      $employee_id = Payroll::createEmployee($model);
      $this->tester->seeRecord('app\models\Employee',
			       ['firstname' => 'Greg',
				'lastname'  => 'Omebije']);
      $this->tester->seeRecord('app\models\EmployeeDeductionsAllowances',[
			     'employee_id' => $employee_id,
			     'deductions_allowances_id' => 1]);
									      
      //$this->tester->seeInDatabase('user',['username' => 'admin@gmail.com']);
      //$this->tester->seeInDatabase('employee_1',['id' => $model->id]);
      //$this->tester->dontSeeRecord('users', ['name' => 'miles']);
    }
    public function testSetYearMonth()
    {
        LoginForm::setYearMonth("2018-01-01");
        $this->assertSame(Yii::$app->session['year_id'], "2018");
        $this->assertSame(Yii::$app->session['month_id'], "1");
        
    }
    /*
    public function testGeneratePayroll()
    {
        LoginForm::setYearMonth("2018-01-01");
        $actual = Payroll::processPayroll('2018-01-01', '13', '16', '0'); 
        $this->assertNotNull($actual, 'Should contain some data');
       
        $expected = [
                    ['AMOS ENGON', '7,000.00'],
                    ['OWUNEBE OJONUGWA', '8,400.00'],
                    ['JOSHUA MAUDE', '9,000.00'],
                    ['EMMANUEL IDOWU', '9,000.00'],
                    ['MALLAM JOEL', '9,000.00']
        ];
        $this->assertSame($expected, $actual);
        $this->tester->seeRecord('app\models\Payroll', 
           ['employee_id' => 52, 'payroll_date' => '2018-01-01', 'basic_salary' => 7000]);
       
    }
   
    public function testPayrollReport()
    {
        $output = new \Codeception\Lib\Console\Output([]);
        
        LoginForm::setYearMonth("2018-01-01");
        $actual = Payroll::processPayroll('2018-01-01', '13', '16', '0'); 
        $this->assertNotNull($actual, 'Should contain some data');       
        $expected = [
                    ['AMOS ENGON', '7,000.00'],
                    ['OWUNEBE OJONUGWA', '8,400.00'],
                    ['JOSHUA MAUDE', '9,000.00'],
                    ['EMMANUEL IDOWU', '9,000.00'],
                    ['MALLAM JOEL', '9,000.00']
        ];
        $this->assertSame($expected, $actual);

        $expected2 = [];
        $row = 1;
        $file = getcwd() . "\app\\tests\\_data\\test.csv";
        if (($handle = fopen($file, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $expected2[] = $data;
                $row++;
            }
            fclose($handle);
        }
        
        LoginForm::setYearMonth("2018-01-01");
        $actual2 = Payroll::getPayrollReport('2018-01-01', '13', '16', '0');
        $this->assertNotNull($actual2, 'Should contain some data');
        //$this->assertSame($expected2, $actual2);
        $this->assertEquals($expected2, $actual2);
        
    }
    */
    /*
    public function testValidation()
    {
        
        $user = new User();
        $user->setName(null);
        $this->assertFalse($user->validate(['username']));

        $user->setName('toolooooongnaaaaaaameeee');
        $this->assertFalse($user->validate(['username']));

        $user->setName('davert');
        $this->assertTrue($user->validate(['username']));
        
        $model = new Employee();
        $model->attributes = [
            'firstname' => 'Greg',
            'lastname' => 'Omebije',
            'grade_level_id' => 12,
            'department_id' => 6,
            'bank_id' => 10,
            'location_id' => 368
        ];
        $this->assertNotNull($model->save());
    }
    
    public function testNotCorrectStateCreated()
    {
        $model = new State();

        $model->attributes = [
            'name' => '',
            'capital_city_name' => '',
            'number_of_local_government_area' => '26',
            'latitude' => '7.7322',
            'longitude' => '8.5391',
            'coords' => '345,428,349,434,359,437,360,450,410,436,379,443,397,443,369,448,382,440,391,441,423,443,421,451,432,446,442,448,457,437,462,422,472,410,471,398,461,385,448,383,439,382,428,386,430,375,422,371,411,372,400,366,385,372,375,375,366,368,354,367,355,376,359,394,361,408,361,417,353,424',
        ];

        $this->assertNotNull($model->save());

        $this->assertNotNull($model->getErrors('name'));
        $this->assertNotNull($model->getErrors('capital_city_name'));

        $this->assertEquals($model->getFirstError('name'), 'Name cannot be blank.');
        $this->assertEquals($model->getFirstError('capital_city_name'), 'Capital City Name cannot be blank.');
        
    }
    
    public function testAlreadyCreatedState()
    {
        $model = new State();

        $model->attributes = [
            'name' => 'FCT',
            'capital_city_name' => 'Abuja',
            'number_of_local_government_area' => '6',
            'latitude' => '7.7322',
            'longitude' => '8.5391',
            'coords' => '345,428,349,434,359,437,360,450,410,436,379,443,397,443,369,448,382,440,391,441,423,443,421,451,432,446,442,448,457,437,462,422,472,410,471,398,461,385,448,383,439,382,428,386,430,375,422,371,411,372,400,366,385,372,375,375,366,368,354,367,355,376,359,394,361,408,361,417,353,424',
        ];

        $this->assertNotNull($model->save());

        $this->assertNotNull($model->getErrors('name'));
        $this->assertNotNull($model->getErrors('capital_city_name'));

        $this->assertEquals($model->getFirstError('name'), 'This name has already been taken.');
        $this->assertEquals($model->getFirstError('capital_city_name'), 'This capital city name has already been taken.');
    }
     * 
     */
}

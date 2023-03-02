<?php
namespace unit\tests;

use Yii;

use app\models\User;
use app\models\Employee;
use app\models\LoginForm;
use app\models\Payroll;

class PayrollUploadTest extends \Codeception\Test\Unit
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

    // tests
    /*
    public function testUpload()
    {
        LoginForm::setYearMonth("2013-02-01");
        $output = new \Codeception\Lib\Console\Output([]);
        $output->writeln('I see NN elements');
        $file = getcwd() . "\app\\tests\\_data\\FEBRUARY_2013_OTHER_ABUJA.csv";
        $actual = Payroll::uploadPayroll($file, '2013-02-01', '14'); 
        
        $this->assertNotNull($actual, 'Should contain some data');   

        $expected = [
                    ['LINUS APOLLOS' ,'7000','1166','0','0','0','1750','5250','1750','875','875','0','0','175','0','1000','0','100','0','0','2832'],
                    ['MICHEAL ABUH' , '7000','0','0','0','0','1750','5250','1750','875','875','0','0','175','0','1000','900','100','0','0','0']
        ];

        $this->assertSame($expected, $actual);
    }
    */
}

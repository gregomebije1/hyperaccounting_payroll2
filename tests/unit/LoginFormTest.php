<?php

namespace tests\unit;

use Yii;
use app\models\LoginForm;
use app\fixtures\UserFixture as UserFixture;

/**
 * Login form test
 */
class LoginFormTest extends \Codeception\Test\Unit
{
    /**
     * @var \common\tests\UnitTester
     */


    public function _before()
    {
        
    }

    public function testLoginNoUser()
    {
        $model = new LoginForm([
            'username' => 'not_existing_username',
            'password' => 'not_existing_password',
        ]);

        $this->assertFalse($model->login());
        $this->assertTrue(Yii::$app->user->isGuest);
        //expect('model should not login user', $model->login())->false();
        //expect('user should not be logged in', Yii::$app->user->isGuest)->true();
    }

    public function testLoginWrongPassword()
    {
        $model = new LoginForm([
            'username' => 'bayer.hudson',
            'password' => 'wrong_password',
        ]);

        //expect('model should not login user', $model->login())->false();
        //expect('error message should be set', $model->errors)->hasKey('password');
        //expect('user should not be logged in', Yii::$app->user->isGuest)->true();
        
        $this->assertFalse($model->login());
        $this->assertNotNull($model->errors);
        $this->assertTrue(Yii::$app->user->isGuest);
    }

    public function testLoginCorrect()
    {
        $model = new LoginForm([
            'username' => 'admin@gmail.com',
            'password' => 'password',
        ]);
        
        $this->assertTrue($model->login(), 'model should login user');
        $this->assertNotNull($model->errors, 'error message should not be set');
        $this->assertFalse(Yii::$app->user->isGuest, 'user should be logged in');
       
        //expect('model should login user', $model->login())->true();
        //expect('error message should not be set', $model->errors)->hasntKey('password');
        //expect('user should be logged in', Yii::$app->user->isGuest)->false();
    }
}

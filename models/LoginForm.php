<?php
namespace app\models;

use Yii;
use yii\base\Model;
use app\models\SignupForm;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;
    public $entity_id;
    public $date;
    
    private $_user;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->username);
        }
        return $this->_user;
    }
    
    public static function setYearMonth($date)
    {
        //Determine year
        $date2 = explode("-", $date);
        $year = $date2[0];
        
        //Determine month. If month is '01' then month_id = 1
        if ((strlen($date2[1]) == 2) && (substr($date2[1], 0, 1) == "0"))
           $month = substr($date2[1], -1);
        else
           $month = $date2[1];
       
        //Check to see if transaction tables exist, if not, create them
        $table = "payroll_" . Yii::$app->session['entity_id'] . "_{$year}_{$month}";
        $query = Yii::$app->db->createCommand('SHOW TABLES LIKE :table')
            ->bindValue(':table', $table)
            ->queryAll();
        if (count($query) == 0) {
           SignupForm::createTransactionTables(Yii::$app->session['entity_id'], $year, $month);
        }
        
        //Set year and month
        Yii::$app->session['year_id'] = $year;
        Yii::$app->session['month_id'] = $month;
    }
}

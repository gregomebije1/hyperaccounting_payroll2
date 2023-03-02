<?php
namespace app\models;

use yii\base\Model;
use app\models\User;
use Yii;

use app\models\CivilServant;
/**
 * Signup form
 */
class SignupForm extends Model
{
    public $firstname;
    public $username;
    public $lastname;
    public $email;
    public $phone_number;
    public $password;
    public $mda_id;
    public $department_id;
    public $grade_level_id;
    public $supervisor_id;
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['firstname', 'trim'],
            ['firstname', 'required'],
            ['firstname', 'string', 'min' => 2, 'max' => 255],

            ['lastname', 'trim'],
            ['lastname', 'required'],
            ['lastname', 'string', 'min' => 2, 'max' => 255],
            
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\backend\models\CivilServant', 'message' => 'This email address has already been taken.'],

            ['phone_number', 'trim'],
            ['phone_number', 'required'],
            ['phone_number', 'number'],
            ['phone_number', 'string', 'max' => 11],
            ['phone_number', 'unique', 'targetClass' => '\backend\models\CivilServant', 'message' => 'This phone number has already been taken.'],
            
            [['mda_id', 'department_id', 'grade_level_id'], 'required'],
            [['mda_id',  'department_id', 'grade_level_id', 'supervisor_id'], 'integer'],
            
            //['password', 'required'],
            //['password', 'string', 'min' => 6],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'mda_id' => 'MDA',
            'department_id' => 'Department',
            'grade_level_id' => 'Grade Level',
        ];
    }
    
    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        
        $user = new User();
        $user->username = $this->email;
        $user->setPassword(md5(time()));
        $user->generateAuthKey();
            
        //return $user->save() ? $user : null;
        if ($user->save()) {
            
            //Create Civil servant
            $civilServant = new CivilServant;
            $civilServant->firstname = $this->firstname;
            $civilServant->lastname = $this->lastname;
            $civilServant->email = $this->email;
            $civilServant->phone_number = $this->phone_number;
            $civilServant->user_id = $user->id;
            $civilServant->department_id = $this->department_id;
            $civilServant->grade_level_id = $this->grade_level_id;
            $civilServant->supervisor_id = $this->supervisor_id;
            $civilServant->save();
            
            //Assign employee Role
            $auth = Yii::$app->authManager;
            $employeeRole = $auth->getRole('employee');
            $auth->assign($employeeRole, $user->getId());
            
            $this->sendEmail($user->id);
            return $user;
        } else 
            return null;
    }
    
    public function sendEmail($id)
    {
        /* @var $user User */
        $user = User::findOne([
            'status' => User::STATUS_ACTIVE,
            'id' => $id,
        ]);

        if (!$user) {
            return false;
        }
        
        if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
            $user->generatePasswordResetToken();
            if (!$user->save()) {
                return false;
            }
        }
        Yii::$app->name = "Nigeria Expenses Management";
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Password reset for ' . Yii::$app->name)
            ->send();
    }
    
    /**
     * Create transaction tables for an entity
     * @param type $entity_id
     * @param type $year
     * @param type $month
     */
    public static function createTransactionTables($entity_id, $year, $month) 
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            //$params = ['{$entity_id}' => $entity_id, ':year' => $year, ':month' => $month];
            $sql = "CREATE TABLE `payroll_{$entity_id}_{$year}_{$month}` (
                      `id` int(11) NOT NULL AUTO_INCREMENT,
                      `employee_id` int(11) NOT NULL,
                      `payroll_date` date NOT NULL,
                      `basic_salary` varchar(100) NOT NULL,
                      `location_id` INT(11) NOT NULL,
                      `branch_id` INT(11) NOT NULL,
                      `created_at` INTEGER NULL,
                      `updated_at` INTEGER NULL,
                      PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
            Yii::$app->db->createCommand($sql)->execute();
            
            $sql = "ALTER TABLE `payroll_{$entity_id}_{$year}_{$month}`
                        ADD CONSTRAINT `fk_payroll_{$entity_id}_{$year}_{$month}_employee_id` FOREIGN KEY (`employee_id`) 
                        REFERENCES `employee_{$entity_id}` (`id`)";
            Yii::$app->db->createCommand($sql)->execute();

            $sql = "ALTER TABLE `payroll_{$entity_id}_{$year}_{$month}`
                        ADD CONSTRAINT `fk_payroll_{$entity_id}_{$year}_{$month}_location_id` FOREIGN KEY (`location_id`) 
                        REFERENCES `location_{$entity_id}` (`id`)";
            Yii::$app->db->createCommand($sql)->execute();

            $sql = "ALTER TABLE `payroll_{$entity_id}_{$year}_{$month}`
                        ADD CONSTRAINT `fk_payroll_{$entity_id}_{$year}_{$month}_branch_id` FOREIGN KEY (`branch_id`) 
                        REFERENCES `branch_{$entity_id}` (`id`)";
            Yii::$app->db->createCommand($sql)->execute();
            
            $sql = "CREATE TABLE `payroll_di_{$entity_id}_{$year}_{$month}` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `payroll_id` int(11) NOT NULL,
                  `deductions_allowances_id` int(11) NOT NULL,
                  `amount` VARCHAR(100) NOT NULL,
                  `created_at` INTEGER NULL,
                  `updated_at` INTEGER NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";               
            Yii::$app->db->createCommand($sql)->execute();

            $sql = "ALTER TABLE `payroll_di_{$entity_id}_{$year}_{$month}`
                        ADD CONSTRAINT `fk_payroll_di_{$entity_id}_{$year}_{$month}_payroll_id` FOREIGN KEY (`payroll_id`) 
                        REFERENCES `payroll_{$entity_id}_{$year}_{$month}` (`id`)";
            Yii::$app->db->createCommand($sql)->execute();

            $sql = "ALTER TABLE `payroll_di_{$entity_id}_{$year}_{$month}`
                        ADD CONSTRAINT `fk_payroll_di_{$entity_id}_{$year}_{$month}_deductions_allowances_id` FOREIGN KEY (`deductions_allowances_id`) 
                        REFERENCES `deductions_allowances_{$entity_id}` (`id`)";
            Yii::$app->db->createCommand($sql)->execute();
        
            $transaction->commit();
            
        } catch(\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }
}

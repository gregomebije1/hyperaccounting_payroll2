<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "employee_deductions_allowances_1".
 *
 * @property integer $employee_id
 * @property integer $deductions_allowances_id
 * @property string $amount
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $id
 *
 * @property DeductionsAllowances1 $deductionsAllowances
 * @property Employee1 $employee
 */
class EmployeeDeductionsAllowances extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'employee_deductions_allowances_'  . Yii::$app->session['entity_id'];
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
            [['employee_id', 'deductions_allowances_id'], 'required'],
            [['employee_id', 'deductions_allowances_id', 'created_at', 'updated_at'], 'integer'],
            [['amount'], 'string', 'max' => 100],
            [['deductions_allowances_id'], 'exist', 'skipOnError' => true, 'targetClass' => DeductionsAllowances::className(), 'targetAttribute' => ['deductions_allowances_id' => 'id']],
            [['employee_id'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::className(), 'targetAttribute' => ['employee_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'employee_id' => 'Employee ID',
            'deductions_allowances_id' => 'Deductions Allowances ID',
            'amount' => 'Amount',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'id' => 'ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeductionsAllowances()
    {
        return $this->hasOne(DeductionsAllowances::className(), ['id' => 'deductions_allowances_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployee()
    {
        return $this->hasOne(Employee::className(), ['id' => 'employee_id']);
    }
}

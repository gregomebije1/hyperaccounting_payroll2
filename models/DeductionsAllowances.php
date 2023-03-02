<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "deductions_allowances_1".
 *
 * @property integer $id
 * @property string $type
 * @property string $name
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property EmployeeDeductionsAllowances1[] $employeeDeductionsAllowances1s
 * @property PayrollDi120121[] $payrollDi120121s
 
 */
class DeductionsAllowances extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'deductions_allowances_' . Yii::$app->session['entity_id'];
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
            [['type', 'name'], 'required'],
            [['type'], 'string'],
            [['created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'name' => 'Name',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployeeDeductionsAllowances()
    {
        return $this->hasMany(EmployeeDeductionsAllowances::className(), ['deductions_allowances_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayrollDs()
    {
        return $this->hasMany(PayrollD::className(), ['deductions_allowances_id' => 'id']);
    }
  
}

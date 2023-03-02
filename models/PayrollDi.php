<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "payroll_di_1_2012_1".
 *
 * @property integer $id
 * @property integer $payroll_id
 * @property integer $deductions_allowances_id
 * @property string $amount
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property DeductionsAllowances1 $deductionsAllowances
 * @property Payroll120121 $payroll
 */
class PayrollDi extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'payroll_di_' .  Yii::$app->session['entity_id'] . '_'
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
            [['payroll_id', 'deductions_allowances_id', 'amount'], 'required'],
            [['payroll_id', 'deductions_allowances_id', 'created_at', 'updated_at'], 'integer'],
            [['amount'], 'string', 'max' => 100],
            [['deductions_allowances_id'], 'exist', 'skipOnError' => true, 'targetClass' => DeductionsAllowances::className(), 'targetAttribute' => ['deductions_allowances_id' => 'id']],
            [['payroll_id'], 'exist', 'skipOnError' => true, 'targetClass' => Payroll1::className(), 'targetAttribute' => ['payroll_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'payroll_id' => 'Payroll ID',
            'deductions_allowances_id' => 'Deductions Allowances ID',
            'amount' => 'Amount',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
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
    public function getPayroll()
    {
        return $this->hasOne(Payroll1::className(), ['id' => 'payroll_id']);
    }
}

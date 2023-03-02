<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "location_1".
 *
 * @property integer $id
 * @property integer $branch_id
 * @property string $name
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Branch1 $branch
 */
class Location extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'location_'  . Yii::$app->session['entity_id'];
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
            [['branch_id', 'name'], 'required'],
            [['branch_id', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 100],
            [['branch_id'], 'exist', 'skipOnError' => true, 'targetClass' => Branch::className(), 'targetAttribute' => ['branch_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'branch_id' => 'Branch',
            'name' => 'Name',
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
    
    public function getEmployees()
    {
        return $this->hasMany(Employee::className(), ['location_id' => 'id'])
                ->Where(['status' => 'Enable']);
    }
    
    /** 
     * @return \yii\db\ActiveQuery 
     */ 
    public function getPayrolls() 
    { 
        return $this->hasMany(Payroll::className(), ['location_id' => 'id']);
    } 
    
    public function getPayroll($date)
    {
        return Payroll::findAll(['location_id' => $this->id, 'payroll_date' => $date]);
    }
    
}

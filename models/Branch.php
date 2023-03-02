<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "branch_1".
 *
 * @property integer $id
 * @property string $name
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Location1[] $location1s
 */
class Branch extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        //return 'branch_1';
        return 'branch_' . Yii::$app->session['entity_id'];
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
            [['name'], 'required'],
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
            'name' => 'Name',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /** 
     * @return \yii\db\ActiveQuery 
     */ 
    public function getEmployees() 
    { 
        return $this->hasMany(Employee::className(), ['branch_id' => 'id']);
    } 
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocations()
    {
        return $this->hasMany(Location::className(), ['branch_id' => 'id']);
    }
    
     /** 
     * @return \yii\db\ActiveQuery 
     */ 
    public function getPayrolls() 
    { 
        return $this->hasMany(Payroll::className(), ['branch_id' => 'id']);
    } 
    
    
}

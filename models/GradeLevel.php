<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "grade_level_1".
 *
 * @property integer $id
 * @property string $name
 * @property string $basic_salary
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Employee1[] $employee1s
 */
class GradeLevel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'grade_level_' . Yii::$app->session['entity_id'];
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
            [['name', 'basic_salary'], 'required'],
            [['created_at', 'updated_at'], 'integer'],
            [['name', 'basic_salary'], 'string', 'max' => 100],
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
            'basic_salary' => 'Basic Salary',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployees()
    {
        return $this->hasMany(Employee::className(), ['grade_level_id' => 'id']);
    }
}

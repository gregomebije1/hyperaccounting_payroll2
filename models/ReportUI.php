<?php

namespace app\models;

use Yii; 
use yii\base\Model;

class ReportUI extends Model
{
    public $date, $branch_id, $location_id, $employee_id;
       
    public function rules()  
    {
        return [
            [['date', 'branch_id', 'location_id', 'employee_id'], 'required'],
            [['date'], 'string']
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'branch_id'           => 'Branch',
            'location_id'         => 'Location',
            'employee_id'         => 'Employee',
            'date' => 'Date',
            
        ];
    }
}    
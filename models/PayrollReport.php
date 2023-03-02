<?php

namespace app\models;

use Yii; 
use yii\base\Model;

class PayrollReport extends Model
{
    public $date, $branch_id, $location_id, $bank_id, $prepared_by, $checked_by, $approved_by;
       
    public function rules()  
    {
        return [
            [['branch_id', 'location_id', 'bank_id'], 'required'],
            [['date'], 'string'],
            [['prepared_by', 'checked_by', 'approved_by'], 'string']
    ];
    }
    
    public function attributeLabels()
    {
        return [
            'branch_id'                 => 'Branch',
            'location_id'               => 'Location',
            'bank_id'                   => 'Bank'
            
        ];
    }
}    
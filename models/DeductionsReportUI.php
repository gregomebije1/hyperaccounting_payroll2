<?php

namespace app\models;

use Yii; 
use yii\base\Model;

class DeductionsReportUI extends Model
{
    public $date, $deductions_allowances_id, $branch_id, $location_id, $bank_id;
       
    public function rules()  
    {
        return [
            [['date', 'deductions_allowances_id', 'branch_id', 'location_id', 'bank_id'], 'required'],
            [['branch_id', 'location_id', 'deductions_allowances_id', 'bank_id'], 'integer'],
            [['date'], 'string']
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'date'                      => 'Date',
            'deductions_allowances_id'  => 'Deductions',
            'branch_id'                 => 'Branch',
            'location_id'               => 'Location',
            'bank_id'                   => 'Bank'
        ];
    }
}    
<?php

namespace app\models;

use Yii; 
use yii\base\Model;

class UpdateAllDeductionsAllowancesUI extends Model
{
    public $branch_id, $location_id, $type, $deductions_allowances_id, $amount;
       
    public function rules()  
    {
        return [
            [['branch_id', 'location_id', 'type', 'deductions_allowances_id', 'amount'], 'required'],
            [['branch_id', 'location_id', 'deductions_allowances_id', 'amount'], 'integer']
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'branch_id'                 => 'Branch',
            'location_id'               => 'Location',
            'type'                      => 'Type',
            'deductions_allowances_id'  => 'Deductions/Allowances',
            'amount'                    => 'Amount'
        ];
    }
}    
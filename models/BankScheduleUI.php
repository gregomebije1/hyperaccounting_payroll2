<?php

namespace app\models;

use Yii; 
use yii\base\Model;

class BankScheduleUI extends Model
{
    public $date, $branch_id, $location_id, $bank_id;
       
    public function rules()  
    {
        return [
            [['date',  'branch_id', 'location_id', 'bank_id'], 'required'],
            [['branch_id', 'location_id', 'bank_id'], 'integer'],
            [['date'], 'string']
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'date'                      => 'Date',
            'branch_id'                 => 'Branch',
            'location_id'               => 'Location',
            'bank_id'                   => 'Bank'
        ];
    }
}    
<?php

namespace app\models;

use Yii; 
use yii\base\Model;

class UploadPayrollUI extends Model
{
    public $date, $branch_id, $payrollFile;
       
    public function rules()  
    {
        return [
            [['date', 'branch_id'], 'required'],
            [['date'], 'string'],
            [['branch_id'], 'integer'],
            [['payrollFile'], 'file', 'extensions' => 'csv', 'checkExtensionByMimeType'=>false],
    ];
    }
    
    public function attributeLabels()
    {
        return [
            'branch_id'                 => 'Branch',
            'date'                      => 'Date',
            'bank_id'                   => 'Payroll File'
            
        ];
    }
}    
<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;


use app\models\Entity;
use app\models\Branch;
use app\models\Location;
use app\models\Bank;
use app\models\Employee;

$this->title = 'Payslip Request';
$this->params['breadcrumbs'][] = $this->title;
?>
<hr />
<style>
    .column-color {
        background-color:#ddf;
    }
    table {
        text-align:center;
    }
</style>
<div class="table-responsive">
    <?php 
     

    foreach ($outputs as $output) {
        if (($model->employee_id != 0) && ($model->employee_id != $output[1]))
           continue;

        $total_earnings = 0;
        $total_deductions = 0;

        $count = 0;
        if (($count == 0) && ($output[1] == "-")){
            continue;
        } else {
            $employee = Employee::findOne(["id" => $output[1]]);
            $bank_account_number = $employee != NULL ? $employee->bank_account_number : '';
            $department_name = $employee != NULL ? $employee->department->name : '';
            $bank_name = $employee != NULL ? $employee->bank->name : '';
          ?>
             <table class="table" style='text-align:center;'>
                <tr style='text-align:center;'>
                  <td style='font-weight:bold;'>Pay Slip</td>
                </tr>
                <tr style='text-align:center;'><td><?=$entity->name?></td></tr>
                <tr style='text-align:center;'><td>BRANCH:<?=Branch::findOne($model->branch_id)->name?></td></tr>
                <tr style='text-align:center;'><td>LOCATION: <?php echo ($model->location_id != 0) ? $locations[0]->name : "All"?> </td></tr>
                <tr style='text-align:center;'><td>PAY PERIOD: <?=$date?></td></tr>
                <tr><td>&nbsp;</td></tr>
                <tr>
                 <td>
                  <table class="table">
                   <tr>
                    <td>Name:</td><td><?=$output[2]?></td>
                    <td>Department:</td><td><?=$department_name?></td>
                   </tr>
                   <tr>
                    <td>Bank</td><td><?=$bank_name?></td>
                    <td>Account No:</td><td><?=$bank_account_number?></td>
                   </tr>
                  </table>
                </td>
               </tr>
               <tr>
                <td>
                 <table class="table table-striped table-bordered table-hover">
                  <tr>
                   <th>Pay Item</th>
                   <th>Earning</th>
                   <th>Deduction</th>
                  </tr>
                  <tr>
                   <td style='text-align:left;'>SALARY</td>
                   <td style='text-align:left;'><?=number_format(isset($output[4]) ? $output[4] : 0, 2)?></td>
                   <td style='text-align:left;'>&nbsp;</td>
                 </tr>
                 <?php 
                 $total_earnings += isset($output[4]) ? $output[4] : 0;
                 
                 $i = 5;
                 foreach ($allowances as $allowance) {
                 ?>
                 <tr>
                   <td style='text-align:left;'><?=strtoupper($allowance->name)?></td>
                   <td style='text-align:left;'><?=number_format(isset($output[$i]) ? $output[$i] : 0, 2)?></td>
                   <td style='text-align:left;'></td>
                 </tr>
                <?php 
                    $total_earnings += isset($output[$i]) ? $output[$i] : 0;
                    $i += 1;
                 }
                 $i += 1;
                 foreach ($deductions as $deduction) {
                    //$temp = explode(',', $output[$i]);
                   // $temp2 = implode("", $temp);
                 ?>
                 <tr>
                   <td style='text-align:left;'><?=strtoupper($deduction->name)?></td>
                   <td style='text-align:left;'></td>
                   <td style='text-align:left;'><?=number_format(isset($output[$i]) ? $output[$i] : 0, 2)?></td>
                 </tr>
                <?php 
                    $total_deductions += isset($output[$i]) ? $output[$i] : 0;
                    $i += 1;
                 }
                ?>                      
                    <tr>
                     <th>Total</th>
                     <th><?=number_format($total_earnings, 2)?></th>
                     <th><?=number_format($total_deductions, 2)?></th>
                    </tr>
                    <tr>
                     <th>Net Pay</th>
                     <th><?=number_format($total_earnings - $total_deductions, 2)?></th>
                     <td>&nbsp;</td>
                    </tr>
                  </table>
                 </td>
                 </tr>
                </table><br /><br />
            <?php 
        }   
        $count += 1;
    }
    ?>
</div>


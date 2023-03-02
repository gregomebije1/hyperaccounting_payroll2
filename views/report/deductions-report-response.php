<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;


use app\models\Entity;
use app\models\Branch;
use app\models\Location;
use app\models\Bank;
use app\models\DeductionsAllowances;

$this->title = 'Bank Schedule';
$this->params['breadcrumbs'][] = $this->title;

 $payrollTable = 'payroll_'  . Yii::$app->session['entity_id'] . "_" . Yii::$app->session['year_id'] . "_" . Yii::$app->session['month_id'];
$payrollDiTable = 'payroll_di_' . Yii::$app->session['entity_id'] . "_" . Yii::$app->session['year_id'] . "_" . Yii::$app->session['month_id'];
        
?>
<hr />
<div class="table-responsive">
    <?php 
        if ($payroll == null) {
            ?>
            <div class="alert alert-danger">
             <strong>Danger!</strong> Payroll has not been prepared for this date
            </div>
        <?php
        }
        /* TODO
        else if ($employees == null) {
            ?>
            <div class="alert alert-danger">
             <strong>Danger!</strong> No employee found
            </div>
        <?php
        }
         * 
         */
        else {
        ?>
            <table>
              <tr style='text-align:center;'>
                <td colspan='5'>
                  <b> ADVICE OF DEDUCTION FROM SALARY - DETAIL SHEET</b>
                </td>
              </tr>
              <tr><td>&nbsp;</td></tr>
              <tr style='text-align:center;'><td colspan='5'><?=$entity->name?></td></tr>
              <tr style='text-align:center;'><td colspan='5'><?=$entity->address?></td></tr>
              <tr style='text-align:center;'><td colspan='5'>BRANCH:
                <?= ($model->branch_id == 0) ? "All" : $branch->name; ?></td></tr>
              
              <tr><td>&nbsp;</td></tr>
              <tr style='text-align:center;'>
                <td colspan='5'>BANK: 
                   <?= ($model->bank_id == 0) ? "All" : $bank->name; ?>
                </td>
              </tr>
              <tr style='text-align:center; border-top:1px solid black;'>
                  <td colspan='5'><h2><?=$deduction->name?> DEDUCTION</b></h2></td>
              </tr>
              <tr style='text-align:center;'>
                <td colspan='5'>
                 <h3>Pay Period: MONTH ENDING: <?php // date('F, Y', $model->date)?></h3>
                </td>
              </tr>
            </table>
            <table class="table table-striped table-bordered table-hover">  
              <?php 
              foreach ($locations as $location) {
                ?>
                <tr><td colspan='5'><br /><br /></td></tr>
                 <tr style='text-align:center;'>
                    <th colspan='5'>
                     LOCATION: <?= $location->name; ?>
                    </th> 
                 </tr>
                 <tr>
                    <th>Pay Roll No.</th>
                    <th>Month/Year</th>
                    <th>Name</th>
                    <th>Ledger Folio</th>
                    <th>Amount</th>
                  </tr>
                  <?php
                    if ($model->bank_id == 0) {
                        $sql = "SELECT pdi.amount, e.firstname, e.lastname FROM {$payrollTable} p 
                            INNER JOIN {$payrollDiTable} pdi ON p.id = pdi.payroll_id
                            INNER JOIN employee_1 e ON e.id = p.employee_id
                            WHERE pdi.deductions_allowances_id = :deductions_allowances_id
                            AND p.location_id = :location_id";
                        
                        $rows = Yii::$app->db->createCommand($sql)
                        ->bindValue(':deductions_allowances_id', $model->deductions_allowances_id)
                        ->bindValue(':location_id', $location->id)
                        ->queryAll();
                    } else {
                        $sql = "SELECT pdi.amount, e.firstname, e.lastname FROM {$payrollTable} p 
                            INNER JOIN {$payrollDiTable} pdi ON p.id = pdi.payroll_id
                            INNER JOIN employee_1 e ON e.id = p.employee_id
                            WHERE pdi.deductions_allowances_id = :deductions_allowances_id
                            AND p.location_id = :location_id
                            AND e.bank_id = :bank_id";
                        $rows = Yii::$app->db->createCommand($sql)
                        ->bindValue(':deductions_allowances_id', $model->deductions_allowances_id)
                        ->bindValue(':location_id', $location->id)
                        ->bindValue(':bank_id', $model->bank_id)
                        ->queryAll();
                    }   
                    if (count($rows) == 0) {
                    ?>
                      <tr style='text-align:center;'>
                        <td colspan='5'>
                         No employee found
                        </td> 
                      </tr> 
                    <?php 
                    } else {
                        $total = 0;
                        foreach ($rows as $row) {
                            if ($row['amount'] > 0) {
                               ?>
                                <tr>
                                 <td>&nbsp;</td>
                                 <td>&nbsp;</td>
                                 <td><?=$row['firstname']?><?=$row['lastname']?></td>
                                 <td>&nbsp;</td> 
                                 <td><?=number_format($row['amount'], 2)?></td>
                                </tr>
                            <?php
                                $total += $row['amount'];		  
                            }
                        }
                        ?>
                        <tr>
                            <td></td>
                            <td></td>
                            <td><h4>ORIGINAL</h4></td>
                            <td>Carried forward </td>
                            <td>=N=<?=number_format($total, 2)?></td>
                        </tr>
                        <?php 
                    }
                }
              ?>
             
            </table>
           <?php
        }
        ?>
</div>

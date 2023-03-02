<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\models\Branch;
use app\models\Bank;

/* @var $this yii\web\View */
/* @var $model app\models\PayrollDI */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Process payroll';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class='row' style='padding-left:1em'>	
   <div class='col-lg-6'>
    <div class='panel panel-default'>
     <div class='panel-heading'>
          <h3><?= Html::encode($this->title) ?></h3>
     </div>
     <div class="panel-body">
         
        <h5>Processing Payroll for Date: <?=$date?> Branch: <?=$branchName?> Location: <?=$locationName?></h5>
        <table class='table table-striped'>
           <thead>
              <tr>
                 <th>S/N</th>
                 <th>Employee</th>
                 <th>Salary</th>
              </tr>
           </thead>
           <tbody>
               <?php 
               $total = 0;
               $counter = 1;
               foreach ($output as $datum) {
                     echo "<tr><td>{$counter}</td><td>{$datum[0]}</td><td>" . number_format($datum[1], 2) . "</td></tr>";
                     $total += $datum[1];
                     $counter += 1;
               }
               ?>
           </tbody>
           <tfoot> 
              <tr><th colspan='2'><h3>Total</h3></th><th><h3><?=number_format($total, 2)?></h3></th></tr>
            </tfoot>
        </table>
     </div>
    </div>
 </div>
</div>

<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;


use app\models\Entity;
use app\models\Branch;
use app\models\Location;
use app\models\Bank;
use app\models\DeductionsAllowances;

$this->title = 'Payroll Report';
$this->params['breadcrumbs'][] = $this->title;
?>
</hr>
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
    $counter = 1;
    $totals = [];

    $j = 1 + count($allowances) + 1 + count($deductions) + 1;
    //var_dump($j);
    //exit;

    for ($i = 0; $i < $j; $i++)
        $totals[$i] = 0;

    foreach ($data as $datum) {
    ?>
    <table class="table table-striped table-bordered table-hover">
        <caption>
            <h3>
                <?=$entity->name?>,<?=$entity->address?><br />
                Date: <?=date('F', mktime(0, 0, 0, Yii::$app->session['month_id'], 1, Yii::$app->session['year_id']))?>
                Branch: <?=$datum[0][0]?>
                Location: <?php echo ($model->location_id != 0) ? $locations[0]->name : "All"?>
                Bank: <?php echo ($model->bank_id != 0) ? $banks->name : "All"?> 
            </h3>
        </caption>
        <thead>
          <tr>
              <th>S/N</th>
              <th>NAMES</th>
              <th class="column-color">BASIC SALARY</th>
              <th colspan='<?=count($allowances)?>'>ALLOWANCES</th>
              <th class="column-color">GROSS SALARY</th>
              <th colspan='<?=count($deductions)?>'>DEDUCTIONS</th>
              <th class="column-color">TOTAL NET SALARY</th>
          </tr> 
          <tr>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
            <th class="column-color">&nbsp;</th>

            <?php

            //List all the allowances
            foreach ($allowances as $allowance) {
                ?>
                <th> <?=strtoupper($allowance->name)?> </th>
                <?php 
            }
            ?>
            <th class='column-color'>&nbsp;</th> <!-- Gross pay-->

            <?php
            //List all the deductions
            foreach ($deductions as $deduction) {
                ?>
                <th> <?=strtoupper($deduction->name)?> </th>
                <?php 
            }
            ?>
            <th class='column-color'>&nbsp;</th> <!--Net salary-->

          </tr>
        </thead>
        <tbody>
            <?php 
            foreach ($datum as $outputs) {                
                ?><tr><?php
                //Display name of location 
                if ($outputs[2] == "-") {
                ?>
                    <th style='text-align:left' colspan="<?=count($allowances) + count($deductions) + 5?>">
                    <?=$outputs[1]?>
                    </th>
                <?php 
                } else {
                    //Track the total
                    if ($outputs[0] == "TOTAL") {
                        for ($i = 4; $i < count($outputs); $i++) {
                            $totals[$i - 4] += $outputs[$i];
                        }
                        echo "<td>SUB TOTAL</td>";
                    }
                    else { //Track the count of employees
                        echo "<td>{$counter}</td>";
                        $counter += 1;  //Increment counter
                    }

                    //Display all information 
                    for ($i = 0; $i < count($outputs); $i++) {

                        //Skip certain columns - Don't display them
                        if (($i == 0) || ($i == 1) || ($i == 3)) {
                            continue;
                        } 
                        else {
                            echo "<td>";
                            echo is_numeric($outputs[$i]) ? number_format($outputs[$i], 2) : $outputs[$i];
                            echo "</td>";
                        }
                    }
                } 
                ?></tr><?php 
            }
            ?>
        </tbody>

    <?php 
    }
    ?>
        <tfoot>
           <tr>
                <th>TOTAL</th>
                <th>&nbsp;</th>
                <?php 
                foreach ($totals as $total) {
                    echo "<th>" . number_format($total, 2) . "</th>";
                }
                ?>
            </tr>
        </tfoot>
    </table>
</div>

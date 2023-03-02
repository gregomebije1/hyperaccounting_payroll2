<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;


use app\models\Entity;
use app\models\Branch;
use app\models\Location;
use app\models\Bank;
use app\models\Employee;

$this->title = 'Bank Schedule';
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
        $counter = 1;
        $total_amount = 0;
        foreach ($data as $datum) {
        ?>
            <table class="table table-striped table-bordered table-hover">
            <caption style="text-align:center;">
                <h3>
                    <?=$entity->name?>, <?=$entity->address?><br />         
                    Branch: <?=$datum[0][0]?>
                    Bank: <?php echo ($model->bank_id != 0) ? $banks->name : "All"?>
                </h3>
            </caption>
            <thead>
                <tr>
                    <th>S/N</th>
                    <th>Names</th>
                    <th>Account Numbers</th>
                    <th>Net Salaries</th>
                </tr> 
            </thead>
            <tbody>
                <?php 
        
                foreach ($datum as $output) {
                    ?><tr><?php 
                    if ($output[2] == "-"){
                    ?>
                        <th style='text-align:left' colspan="4">
                        <?=$output[1]?>
                        </th>
                    <?php 
                    //} else if ($output[0] != "TOTAL") {
                    } else {
                        if ($output[0] == "TOTAL") {
                            $total_amount += $output[count($output) - 1];
                            echo "<td>TOTAL</td>";
                        }
                        else { //Track the count of employees
                            echo "<td>{$counter}</td>";
                            $counter += 1; //Increment counter
                        }
                        ?>
                        <td><?=$output[2]?></td>
                        <td><?=$output[3]?></td>
                        <td><?=number_format($output[count($output) - 1], 2)?></td>
                        <?php    
                    }
                    ?></tr><?php 
                }
                ?>
            </tbody>
            </table>
            
        <?php
        }
    ?>
    <table class="table table-striped table-bordered table-hover">
        <tbody>
            <tr><td><h1>Total</h1></td><td><h1><?=number_format($total_amount,2)?></h1></td></tr>
        </tbody>
    </table>
</div>

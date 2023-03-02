<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\EmployeeDeductionsAllowances */

$this->title = 'Update Employee Deductions Allowances:';
$this->params['breadcrumbs'][] = ['label' => 'Employee Deductions Allowances', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<ul class="nav nav-tabs">
  <li><a href="<?=Url::toRoute(['employee/update', 'id' => $employeeId])?>">Employee</a></li>
  <li <?=$type == "Allowances" ? 'class="active"' : ''?>><a href="<?=Url::toRoute(['employee-deductions-allowances/update', 'type' => 'Allowances', 'employeeId' => $employeeId])?>">Allowances</a></li>
  <li <?=$type == "Deductions" ? 'class="active"' : ''?>><a href="<?=Url::toRoute(['employee-deductions-allowances/update', 'type' => 'Deductions', 'employeeId' => $employeeId])?>">Deductions</a></li>
  <li><a href="<?=Url::toRoute(['employee/payee', 'id' => $employeeId])?>">Payee</a></li>
</ul>

<hr />
<div class="employee-deductions-allowances-update">

    <?php $form = ActiveForm::begin(); ?>
    <table>
    <?php 
        $arr = array('PAYE', 'HOUSING', 'UTILITY', 'MEAL', 'ENTERTAINMENT', 'TRANSPORT');
        foreach ($models as $index => $model) {
        ?>  
            <tr>
                <td>
                    <?= $model->deductionsAllowances->name ?>
                </td>
                <td>
                    <?php 
                     if (in_array($model->deductionsAllowances->name, $arr)) {
                        echo $form->field($model, "[$index]amount")->
                             textInput(['readonly' => 'readonly', 
                                'value' => number_format($model->employee->calculatePayeBasic($model->deductionsAllowances->name), 2)])->label("");
                     } else { 
                         echo $form->field($model, "[$index]amount")->textInput()->label("");
                     }
                     ?>
                </td>
            </tr>
        <?php 
        }
        ?>
    </table>
    <div class="form-group">
        <?= Html::submitButton('Update', ['class' => 'btn btn-primary']) ?>
            
    </div>

    <?php ActiveForm::end(); ?>
</div>

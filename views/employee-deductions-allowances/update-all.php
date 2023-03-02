<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Alert;
  
use app\models\Branch;
use app\models\Location;

/* @var $this yii\web\View */
/* @var $model app\models\EmployeeDeductionsAllowances */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="employee-deductions-allowances-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php 
        echo $form->field($model, 'branch_id')->dropDownList(
          ArrayHelper::merge([""=>""],ArrayHelper::map(Branch::find()->all(),'id','name')),
          ['class' => 'form-control']);
    ?>
    <?php 
        echo $form->field($model, 'location_id')->dropDownList([], ['class' => 'form-control'])
    ?>

    <?= $form->field($model, 'type')->dropDownList([ 'Deductions' => 'Deductions', 'Allowances' => 'Allowances', ], ['prompt' => '']) ?>
    <?php 
        echo $form->field($model, 'deductions_allowances_id')->dropDownList([], ['class' => 'form-control'])
    ?>

    <?= $form->field($model, 'amount')->textInput(['maxlength' => true]) ?>
    <div class="form-group">
        <?= Html::submitButton('Update', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
$url = Url::toRoute(['branch/get-location', 'branchId' => ""]);
$url2 = Url::toRoute(['deductions-allowances/get-type', 'type' => ""]);
$script = <<< JS
    $(document).ready(function(){
        $("#updatealldeductionsallowancesui-branch_id").change(function(){
            var optionSelected = $(this).find('option:selected');
            var optValueSelected = optionSelected.val();

            $.get("{$url}" + optValueSelected, function(data, status){
                    
                response = JSON.parse(data);
                var select = $("#updatealldeductionsallowancesui-location_id");
                select.empty();   
        
                select.append('<option value="0">All</option>');
                if(response.success == 1) {
                    //iterate over the data and append a select option
                    $.each(response.data, function(key, val){ 
                      select.append('<option value="' + val.id + '">' + val.name + '</option>');
                    });
                } else {
                    alert(response.message);
                    select.empty();
                }
            });
        });
            
        $("#updatealldeductionsallowancesui-type").change(function(){
            var optionSelected = $(this).find('option:selected');
            var optValueSelected = optionSelected.val();

            $.get("{$url2}" + optValueSelected, function(data, status){
                    
                response = JSON.parse(data);
                var select = $("#updatealldeductionsallowancesui-deductions_allowances_id");
                select.empty();   
        
                if(response.success == 1) {
                    //iterate over the data and append a select option
                    $.each(response.data, function(key, val){ 
                      select.append('<option value="' + val.id + '">' + val.name + '</option>');
                    });
                } else {
                    alert(response.message);
                    select.empty();
                }
            });
        });
    });
JS;
$this->registerJs($script);
?>

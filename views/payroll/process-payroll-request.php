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
<style type="text/css">
.ui-datepicker-calendar {
        display: none;
    }
</style>
<hr />
<div class='row' style='padding-left:1em'>	
   <div class='col-lg-6'>
    <div class='panel panel-default'>
     <div class='panel-heading'>
          <h3><?= Html::encode($this->title) ?></h3>
     </div>
     <div class="panel-body">
    <?php $form = ActiveForm::begin() ?>
    <?= $form->field($model, 'date')->widget(\yii\jui\DatePicker::classname(), [
           'clientOptions' => [
                       'changeMonth'=>true,
                       'changeYear'=>true, 
                       'yearRange'=> '2012:+1',
                       'showButtonPanel' => true,
                    ],
           'options'=> [ 'class'=>'form-control',
                        'readonly'=>true,
                        'style'=>'background-color:#fff']
          ]) 
     ?>
     <?php 
        echo $form->field($model, 'branch_id')->dropDownList(
          ArrayHelper::merge([""=>""],ArrayHelper::map(Branch::find()->all(),'id','name')),
          ['class' => 'form-control']);
    ?>
    <?php 
        echo $form->field($model, 'location_id')->dropDownList([], ['class' => 'form-control'])
    ?>
    <?php 
        echo $form->field($model, 'employee_id')->dropDownList([], ['class' => 'form-control'])
    ?>

    <div class="form-group">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
     </div>
    </div>
 </div>
</div>
<?php

$url = Url::toRoute(['branch/get-location', 'branchId' => ""]);
$url2 = Url::toRoute(['location/get-employee', 'locationId' => ""]);
$script = <<<JS
    $(document.body).on('click', '.ui-datepicker-close', function (e) {
        var value = $('.ui-datepicker-year :selected').text();
        var value2 = $('.ui-datepicker-month :selected').val();
        $('#reportui-date').val(value + '-' + convertMonth(value2) + '-01');
    });
        
    function convertMonth(month) {
        var mth = (parseInt(month) + 1) + "";
        return mth.length == 1 ? ("0" + mth) : mth;
    }
    $(document).ready(function(){
        $("#reportui-branch_id").change(function(){
            var optionSelected = $(this).find('option:selected');
            var optValueSelected = optionSelected.val();

            $.get("{$url}" + optValueSelected, function(data, status){
                   
                response = JSON.parse(data);
                var select = $("#reportui-location_id");
                select.empty();   
                select.append('<option value="0">All</option>');
            
                var select2 = $("#reportui-employee_id");
                select2.empty();   
                select2.append('<option value="0">All</option>');
            
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
            
        $("#reportui-location_id").change(function(){
            var optionSelected = $(this).find('option:selected');
            var optValueSelected = optionSelected.val();

            $.get("{$url2}" + optValueSelected, function(data, status){
                    
                response = JSON.parse(data);
                var select = $("#reportui-employee_id");
                select.empty();   
        
                select.append('<option value="0">All</option>');
                if(response.success == 1) {
                    //iterate over the data and append a select option
                    $.each(response.data, function(key, val){ 
                      select.append('<option value="' + val.id + '">' + val.firstname + ' ' + val.middlename + ' ' + val.lastname + '</option>');
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
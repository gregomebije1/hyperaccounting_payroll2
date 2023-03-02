<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\models\Branch;
use app\models\Bank;
use yii\web\View;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Upload Payroll';
$this->params['breadcrumbs'][] = ['label' => 'Payroll', 'url' => ['']];
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
     <!--<div class='panel-heading'></div>-->
     <div class="panel-body">
     <?php $form = ActiveForm::begin([
        //'id' => "{$action}",
        'options' => [
           'enctype' => 'multipart/form-data'
        ],
        //'action' => "{$action}"
      ])
    ?>
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
     <?= $form->field($model, 'payrollFile')->fileInput() ?>
       
     <hr />
     <div class="form-group">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-success', 'id' => 'myButton']) ?>
     </div>

     <?php ActiveForm::end(); ?>
     </div>
    </div>
 </div>
</div>
<?php 
$script = <<<JS
    $(document.body).on('click', '.ui-datepicker-close', function (e) {
        var value = $('.ui-datepicker-year :selected').text();
        var value2 = $('.ui-datepicker-month :selected').val();
        $('#uploadpayrollui-date').val(value + '-' + convertMonth(value2) + '-01');
    });
        
    function convertMonth(month) {
        var mth = (parseInt(month) + 1) + "";
        return mth.length == 1 ? ("0" + mth) : mth;
    }
JS;
$this->registerJs($script);
?>
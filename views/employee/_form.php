<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Branch;
use app\models\Bank;
use app\models\GradeLevel;
use app\models\Department;
use app\models\Location;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Employee */
/* @var $form yii\widgets\ActiveForm */
?>
<?php $form = ActiveForm::begin(); ?>
    
<div class="row">
    <div class="col-sm-6">
   
        <?= $form->field($model, 'lastname')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'firstname')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'middlename')->textInput(['maxlength' => true]) ?>

        <?php 
            echo $form->field($model, 'grade_level_id')->dropDownList(
            ArrayHelper::merge(["0"=>""],ArrayHelper::map(GradeLevel::find()->all(),'id','name')),
            ['class' => 'form-control']);
        ?>
        <?php 
            echo $form->field($model, 'department_id')->dropDownList(
            ArrayHelper::merge(["0"=>""],ArrayHelper::map(Department::find()->all(),'id','name')),
            ['class' => 'form-control']);
        ?>
       <?php 
            echo $form->field($model, 'bank_id')->dropDownList(
            ArrayHelper::merge(["0"=>""],ArrayHelper::map(Bank::find()->all(),'id','name')),
            ['class' => 'form-control']);
        ?>

        <?= $form->field($model, 'bank_account_number')->textInput(['maxlength' => true]) ?>
        
        <?php 
            echo $form->field($model, 'branch_id')->dropDownList(
                ArrayHelper::merge(["0"=>""],ArrayHelper::map(Branch::find()->all(),'id','name')),
                ['class' => 'form-control']);
            
            if ($model->isNewRecord)
            {
                 echo $form->field($model, 'location_id')->dropDownList([], ['class' => 'form-control']);
            }
            else
            {
               echo $form->field($model, 'location_id')->dropDownList(
                   ArrayHelper::merge(["0"=>""],ArrayHelper::map(Location::find()->all(),'id','name')),
                   ['class' => 'form-control']);          
            }
        ?>
        
        <?php 
         /*if (!$model->isNewRecord)
         {
            echo "
              <p><b>Branch</b> " . $model->location->branch->name . "</p>";  
         }
         
         echo "<b>Choose branch</b> " . Html::DropDownList('branch_name', null, 
                ArrayHelper::merge([""=>""],ArrayHelper::map(Branch::find()->all(),'id','name')),
                ['class' => 'form-control', 'id' => 'branch_id']);
         
          * 
          */
        ?>
        <?= $form->field($model, 'marital_status')->dropDownList([
            'MARRIED' => 'MARRIED', 'SINGLE' => 'SINGLE', ], ['prompt' => '']) ?>
   

        <?= $form->field($model, 'date_of_birth')->widget(\yii\jui\DatePicker::classname(), [
           'dateFormat' => 'yyyy-MM-dd',
           'clientOptions' => [
                       'changeMonth'=>true,
                       'changeYear'=>true, 
                       'yearRange'=> '1940:+1'],
           'options'=>[ 'class'=>'form-control','readonly'=>true,'style'=>'background-color:#fff']
          ]) ?>

        <?= $form->field($model, 'place_of_birth')->
	     textInput(['maxlength' => true])?>

        <?= $form->field($model, 'nationality')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'passport_no')->textInput(['maxlength' => true]) ?>
        
        <?= $form->field($model, 'national_identity_card_no_or_residential_permit_no')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'local_govt_area_and_state_of_origin')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'name_of_next_of_kin_and_relationship')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'address_and_telephone_next_of_kin')->textarea(['rows' => 6]) ?>
    </div>
        
    <div class="col-sm-6">
        
          <?= $form->field($model, 'permanent_home_or_family_address')->textarea(['rows' => 6]) ?>

     
        <?= $form->field($model, 'abuja_residential_address')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'home_telephone_number')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'mobile_telephone_number')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'secondary_school_qualification')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'post_secondary_school_qualification')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'previous_employer')->textarea(['rows' => 6]) ?>

	<?= $form->field($model, 'date_of_first_appointment')->widget(\yii\jui\DatePicker::classname(), [
           'dateFormat' => 'yyyy-MM-dd',
           'clientOptions' => [
                       'changeMonth'=>true,
                       'changeYear'=>true, 
                       'yearRange'=> '1940:+1'],
           'options'=>[ 'class'=>'form-control','readonly'=>true,'style'=>'background-color:#fff']
          ]) ?>

        <?= $form->field($model, 'starting_position')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'penalities')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'passport')->textInput(['maxlength' => true]) ?>
         <?= $form->field($model, 'status')->dropDownList([
            'Enable' => 'Enable', 'Disable' => 'Disable', ], ['prompt' => '']) ?>

    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>

<?php

$url = Url::toRoute(['branch/get-location', 'branchId' => ""]);
$script = <<< JS
    $(document).ready(function(){
        $("#employee-branch_id").change(function(){
            var optionSelected = $(this).find('option:selected');
            var optValueSelected = optionSelected.val();

            $.get("{$url}" + optValueSelected, function(data, status){
                    
                response = JSON.parse(data);
                var select = $("#employee-location_id");
                select.empty();   
        
                select.append('<option value="0"></option>');
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

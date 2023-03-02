<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PayeeItem */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="payee-item-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?php 
        echo $form->field($model, 'payee_item_type')->dropDownList([
            'percentage' => 'percentage', 'value' => 'value'],
        ['class' => 'form-control']);
    ?>
    <?php 
        echo $form->field($model, 'payee_item_group')->dropDownList([
            'paye' => 'paye', 'reliefs' => 'reliefs', 'exceptions' => 'exceptions'],
        ['class' => 'form-control']);
    ?>
 
    <?= $form->field($model, 'amount')->textInput(['maxlength' => true]) ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

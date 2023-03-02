<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\Employee */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
                'class' => 'form-inline'
          ]
    ]); ?>

    <?= $form->field($model, 'search')->textInput(['maxlength' => true, 
            'class' => 'form-inline', 'size' => '45'])->label("") ?>

    <?= Html::submitButton('Search', ['class' => 'btn btn-primary', 'id' => 'search']) ?>

    <?= Html::a('Reset', ['/employee/index'], ['class' => 'btn btn-default']) ?>

<?php ActiveForm::end(); ?>
    
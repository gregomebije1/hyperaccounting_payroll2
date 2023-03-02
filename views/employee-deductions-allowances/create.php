<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\EmployeeDeductionsAllowances */

$this->title = 'Create Employee Deductions Allowances';
$this->params['breadcrumbs'][] = ['label' => 'Employee Deductions Allowances', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="employee-deductions-allowances-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

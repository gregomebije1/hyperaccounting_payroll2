<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DeductionsAllowances */

$this->title = 'Update Deductions Allowances: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Deductions Allowances', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="deductions-allowances-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

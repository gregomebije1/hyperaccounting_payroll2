<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\PayrollDI */

$this->title = 'Create Payroll Di';
$this->params['breadcrumbs'][] = ['label' => 'Payroll Dis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payroll-di-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

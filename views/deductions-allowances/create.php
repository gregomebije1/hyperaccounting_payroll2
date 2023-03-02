<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\DeductionsAllowances */

$this->title = 'Create Deductions Allowances';
$this->params['breadcrumbs'][] = ['label' => 'Deductions Allowances', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="deductions-allowances-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PayeeItem */

$this->title = 'Update Payee Item: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Payee Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="payee-item-update">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

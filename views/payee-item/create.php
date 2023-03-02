<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\PayeeItem */

$this->title = 'Create Payee Item';
$this->params['breadcrumbs'][] = ['label' => 'Payee Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payee-item-create">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\AuditTrail */

$this->title = 'Create Audit Trail';
$this->params['breadcrumbs'][] = ['label' => 'Audit Trails', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="audit-trail-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

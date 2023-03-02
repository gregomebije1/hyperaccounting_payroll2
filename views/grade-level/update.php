<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\GradeLevel */

$this->title = 'Update Grade Level: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Grade Levels', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="grade-level-update">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

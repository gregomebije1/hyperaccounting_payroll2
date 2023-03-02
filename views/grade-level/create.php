<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\GradeLevel */

$this->title = 'Create Grade Level';
$this->params['breadcrumbs'][] = ['label' => 'Grade Levels', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="grade-level-create">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

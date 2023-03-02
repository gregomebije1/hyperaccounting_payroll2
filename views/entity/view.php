<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Entity */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Entities', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="entity-view">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <!--<?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>-->
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            'name',
            'address:ntext',
            'phone',
            'email:email',
            'web',
            'logo',
             [
                'label' => 'Created At',
                'value' => function ($model) {
                        return date("Y-m-d", $model->created_at);
                 },
            ],
            [
                'label' => 'Updated At',
                'value' => function ($model) {
                        return date("Y-m-d", $model->updated_at);
                 },
            ], 
        ],
    ]) ?>

</div>

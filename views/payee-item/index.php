<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\PayeeItem */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Payee Items';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payee-item-index">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Payee Item', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            'payee_item_group',
            [
                'label' => 'Value',
                'value' => function ($model) {
                        return $model->payee_item_type == 'percentage' ? "{$model->amount}%" : number_format($model->amount);
                 },
            ],
            // 'created_at',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

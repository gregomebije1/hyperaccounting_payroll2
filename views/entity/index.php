<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\Entity */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Entities';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="entity-index">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Entity', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'address:ntext',
            'phone',
            'email:email',
            // 'web',
            // 'logo',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

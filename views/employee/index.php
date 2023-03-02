<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\Employee */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Employees';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="employee-index">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <div class="row">
      <div class="col-sm-2">
        <?= Html::a('Create Employee', ['create'], ['class' => 'btn btn-success']) ?>
      </div>
      <div class="col-sm-6">
          <?php  echo $this->render('_search', ['model' => $searchModel]); ?>
      </div>
    </div>
    <br />
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'lastname',
            'firstname',
            'middlename',
            [
                'label' => 'Branch',
                'value' => function ($model) {
                     return isset($model['branch_name']) ? $model['branch_name'] : '';
                 },
            ],
            [
                'label' => 'Location',
                'value' => function ($model) {
                  return isset($model['location_name']) ? $model['location_name'] : '';
                 },
            ],
            [
                'label' => 'Bank',
                'value' => function ($model) {
                        return isset($model['bank_name']) ? $model['bank_name'] : '';
                 },
            ],
            [
                'label' => 'Status',
		'format' => 'html',
                'value' => function ($model) {
                  return $model['status'] == 'Disable'
		    ? Html::tag('p', Html::encode($model['status'] . "d"),
		       ['class' => 'btn btn-danger'])
		    : Html::tag('p', Html::encode($model['status'] . "d"),
		       ['class' => 'btn btn-success']);	
                 },
            ],
 
          ['class' => 'yii\grid\ActionColumn',
             'header'            => 'Actions',
             'headerOptions'     => ['width' => '20%'],
             'template' => '{view} {update}',
             'buttons' => [
                'view'    => function($url, $model) {
                     return Html::a('View',
		       ['view', 'id' => $model['id']],
			 ['class' => 'btn btn-xs btn-primary']);
                },
                'update'    => function($url, $model) {
                     return Html::a('Update',
		       ['update', 'id' => $model['id']],
			  ['class' => 'btn btn-xs btn-warning']);
                }
	     ],
	   ],
        ],
    ]); ?>
</div>

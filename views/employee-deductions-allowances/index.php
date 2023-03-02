<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\EmployeeDeductionsAllowances */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Employee Deductions Allowances';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="employee-deductions-allowances-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Employee Deductions Allowances', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'employee_id',
            'deductions_allowances_id',
            'amount',
            // 'id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

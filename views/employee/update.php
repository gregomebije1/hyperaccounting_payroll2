<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\UrlManager;

/* @var $this yii\web\View */
/* @var $model app\models\Employee */

?>

<?php 
$this->title = 'Update Employee: ' . $model->firstname . ' ' . $model->lastname;
$this->params['breadcrumbs'][] = ['label' => 'Employees', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<ul class="nav nav-tabs">
  <li class="active"><a href="<?=Url::toRoute(['employee/update', 'id' => $model->id])?>">Employee</a></li>
  <li><a href="<?=Url::toRoute(['employee-deductions-allowances/update', 'type' => 'Allowances', 'employeeId' => $model->id])?>">Allowances</a></li>
  <li><a href="<?=Url::toRoute(['employee-deductions-allowances/update', 'type' => 'Deductions', 'employeeId' => $model->id])?>">Deductions</a></li>
  <li><a href="<?=Url::toRoute(['employee/payee', 'id' => $model->id])?>">Payee</a></li>
</ul>
<hr />
<div class="employee-update">
    <!--<h1><?= Html::encode($this->title) ?></h1>-->
        
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
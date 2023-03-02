<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;


use app\models\Entity;
use app\models\Branch;
use app\models\Location;
use app\models\Bank;
use app\models\DeductionsAllowances;

$this->title = 'Uploading Payroll';
$this->params['breadcrumbs'][] = $this->title;
?>
<hr />
<style>
    .column-color {
        background-color:#ddf;
    }
    table {
        text-align:center;
    }
</style>
<div class="table-responsive">
    <h1>Uploaded</h1>
</div>

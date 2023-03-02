<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Employee */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Employees', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="employee-view">

   <!-- <h1><?= Html::encode($this->title) ?></h1>-->

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            'lastname',
            'firstname',
            'middlename',
            [
                'label' => 'Grade Level',
                'value' => function ($model) {
                        return $model->gradeLevel->name;
                 },
            ],
            [
                'label' => 'Department',
                'value' => function ($model) {
                        return $model->department->name;
                 },
            ],
            [
                'label' => 'Branch',
                'value' => function ($model) {
                        return $model->branch->name;
                 },
            ],
            [
                'label' => 'Location',
                'value' => function ($model) {
                        return $model->location->name;
                 },
            ],
            [
                'label' => 'Bank',
                'value' => function ($model) {
                        return $model->bank->name;
                 },
            ],
            'bank_account_number',
            
            'marital_status',
            'date_of_birth',
            'place_of_birth',
            'nationality',
            'passport_no',
            'national_identity_card_no_or_residential_permit_no',
            'local_govt_area_and_state_of_origin:ntext',
            'name_of_next_of_kin_and_relationship',
            'address_and_telephone_next_of_kin:ntext',
            'permanent_home_or_family_address:ntext',
            'abuja_residential_address:ntext',
            'home_telephone_number',
            'mobile_telephone_number',
            'secondary_school_qualification:ntext',
            'post_secondary_school_qualification:ntext',
            'previous_employer:ntext',
            'date_of_first_appointment',
            'starting_position',
            'penalities:ntext',
            'passport',
            'status',
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

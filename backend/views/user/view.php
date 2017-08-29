<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'View User Profile : ' . $model->username;
?>
<div class="user-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'first_name',
            'last_name',
            'phone',
            'username',
            [
                'label' => 'User Type',
                'value' => ($model->user_type==0) ? 'Admin' : 'Front User'
            ],
            'email:email',
            'image',
            'last_login',
            [
                'label' => 'Status',
                'value' => ($model->status==0) ? 'Inactive' : ($model->status==1) ? 'Active' : 'Not Activated Yet' 
            ],
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>

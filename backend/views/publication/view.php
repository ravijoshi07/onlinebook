<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Publication Houses', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'View Publication Houses : ';
?>
<div class="user-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'address',
            'contact',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>

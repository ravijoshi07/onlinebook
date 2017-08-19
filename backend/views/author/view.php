<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Author', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'View Author : ';
?>
<div class="user-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'stream',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>

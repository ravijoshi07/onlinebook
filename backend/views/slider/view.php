<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Slider', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'View Slider : ';
?>
<div class="user-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'image',
                [
                    'attribute' =>  'image',
                    'format'    =>  'image',
                    'filter'    =>  '',
                    'value'     =>  function($data) { return \Yii::$app->request->BaseUrl.'/uploads/banner/'.$data->image; }
                ],
            'product_id',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>

<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Celebrity */

$this->title = 'View Celebrity';
$this->params['breadcrumbs'][] = ['label' => 'Celebrities', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="celebrity-view">

    <div class="row">
        <div class="col-md-12">
            <div class="box">

                <div class="box-header with-border">
                    <h3 class="box-title"><?= Html::encode($model->name) ?></h3>

                    <div class="box-tools pull-right">
                        <?php ?>
                    </div>

                </div>

                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12"> 

                            <?=
                            DetailView::widget([
                                'model' => $model,
                                'attributes' => [
                                    'id',
                                    'name',
                                    [
                                        'attribute' =>  'image',
                                        'format'    =>  'image',
                                        'filter'    =>  '',
                                        'value'     =>  function($data) { return \Yii::$app->request->BaseUrl.'/uploads/celebrity/'.$data->image; }
                                    ],
                                    'description:ntext',
                                    'product_id',
                                    'created_at',
                                    'updated_at',
                                    [
                                        'label' => 'Status',
                                        'value' => ($model->status == 0) ? 'Inactive' : ($model->status == 1) ? 'Active' : 'Not Activated Yet'
                                    ],
                                ],
                            ])
                            ?>
                        </div>
                    </div>
                </div>

                <div class="box-footer">
                    <div class="row">
                        <div class="col-md-12">

                            <div class="pull-right">

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

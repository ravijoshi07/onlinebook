<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Countries';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="country-index">

    <div class="row">
        <div class="col-md-12">
            <div class="box">

                <div class="box-header with-border">
                    <h3 class="box-title">Country Listing</h3>

                    <div class="box-tools pull-right">

                    </div>
                </div>
                <?php
                Pjax::begin([
                    'enablePushState' => FALSE
                ]);
                ?>    

                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <?=
                                GridView::widget([
                                    'dataProvider' => $dataProvider,
                                    'filterModel' => $model,
                                    'layout' => "<div class='table-responsive'>{items}</div>\n<div class='pull-left'>{summary}</div><div class='pull-right'>{pager}</div>",
                                    
                                    'columns' => [
                                        ['class' => 'yii\grid\SerialColumn'],
                                        'name',
                                        'code',
                                        [
                                            'class' => 'yii\grid\ActionColumn',
                                            'template' => '{status_change} {manage}',
                                            'buttons' => [
                                                'status_change' => function ($url,$model) {
                                                    return Html::a(
                                                        ($model->status==1) ? '<i class="fa fa-dot-circle-o text-success">&nbsp;</i>' : '<i class="fa fa-dot-circle-o text-red">&nbsp;</i>',
                                                        ['country/status', 'id' => $model->id, 'prevState'=>$model->status],  
                                                        [
                                                            'title' => ($model->status==1) ? 'Inactivate' : 'Activate',
                                                            'data-pjax' => '1',
                                                        ]
                                                    );
                                                },

                                                'manage' => function ($url,$model) {
                                                    return Html::a(
                                                        '<i class="fa fa-cog">&nbsp;</i>',
                                                        ['country/state', 'country_id' => $model->id],
                                                        [
                                                            'title' => 'Manage Subcategories',
                                                            'data-pjax' => '0',
                                                        ]
                                                    );
                                                },
                                            ],
                                        ],
                                    ],
                                ]);
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="box-footer">
                        <div class="row">
                            <div class="col-md-12">

                                <div class="pull-right">
                                    <?php // echo LinkPager::widget(['pagination' => $pagination])  ?>
                                </div>
                            </div>
                        </div>
                    </div>  

                <?php Pjax::end(); ?>

            </div>
        </div>
    </div>
</div>

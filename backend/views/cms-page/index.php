<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cms Pages';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="cms-page-index">

    
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                
                <div class="box-header with-border">
                    <h3 class="box-title">Cms Page Listing</h3>
                    
                    <div class="box-tools pull-right">
                        <?php echo Html::a(
                                '<i class="fa fa-plus-circle">&nbsp;</i> Add', 
                                Yii::$app->urlManager->createUrl('cms-page/create'),
                                [
                                    'title' => 'Create Cms Page', 
                                    'class' => 'btn btn-primary btn-flat',
                                    'id'=>'createBtn'
                                ]
                            ); 
                        ?>
                    </div>
                    
                </div>
                
                
                <?php Pjax::begin([
                    'enablePushState'=>FALSE            
                ]); ?>    
                
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                
                                <?= GridView::widget([
                                    'dataProvider' => $dataProvider,
                                    'filterModel' => $model,
                                    'layout' => "<div class='table-responsive'>{items}</div>\n<div class='pull-left'>{summary}</div><div class='pull-right'>{pager}</div>",
                                    'columns' => [
                                        ['class' => 'yii\grid\SerialColumn'],
                                        'title',
                                        'unique_name',
                                        'content:html',
                                        'created_at',
                                        [
                                            'class' => 'yii\grid\ActionColumn',
                                            //'template' => '{status_change} {view} {update} {delete}',
                                            'template' => '{status_change} {view} {update}',
                                            'buttons' => [
                                                'status_change' => function ($url,$model) {
                                                    return Html::a(
                                                        ($model->status==1) ? '<i class="fa fa-dot-circle-o text-success">&nbsp;</i>' : '<i class="fa fa-dot-circle-o text-red">&nbsp;</i>',
                                                        ['cms-page/status', 'id' => $model->id, 'prevState'=>$model->status],  
                                                        [
                                                            'title' => ($model->status==1) ? 'Inactivate' : 'Activate',
                                                            'data-pjax' => '1',
                                                        ]
                                                    );
                                                },
                                            ],
                                        ],
                                    ],
                                ]); ?>
                                
                            </div>
                        </div>
                    </div>

                    <div class="box-footer">
                        <div class="row">
                            <div class="col-md-12">
                                
                                <div class="pull-right">
                                    <?php // echo LinkPager::widget(['pagination' => $pagination]) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                
                    <?php 
                        yii\bootstrap\Modal::begin([
                            'headerOptions' => ['id' => 'modalHeader'],
                            'id'=>'modal',            
                            'clientOptions' => ['backdrop' => 'static', 'keyboard' => FALSE]
                        ]);

                        yii\bootstrap\Modal::end();
                    ?>
                
                <?php Pjax::end(); ?>
                
            </div>
        </div>
    </div>
</div>

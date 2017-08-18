<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Celebrities';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="celebrity-index">

    <div class="row">
        <div class="col-md-12">
            <div class="box">
                
                <div class="box-header with-border">
                    <h3 class="box-title">Celebrity Listing</h3>
                    
                    <div class="box-tools pull-right">
                        <?php echo Html::a(
                                '<i class="fa fa-plus-circle">&nbsp;</i> Add', 
                                Yii::$app->urlManager->createUrl('celebrity/create'),
                                [
                                    'title' => 'Create Celebrity', 
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
                                        [
                                            'attribute' =>  'image',
                                            'format'    =>  'image',
                                            'filter'    =>  '',
                                            'value'     =>  function($data) { return \Yii::$app->request->BaseUrl.'/uploads/celebrity/'.$data->image; }
                                        ],
                                        'name',
                                        [
                                            'attribute' =>  'description',
                                            'format'    =>  'ntext',
                                            'value'     =>  function($data) { return wordwrap($data->description,100,"\n",true); },
                                        ],
                                        'created_at',
                                        [
                                            'class' => 'yii\grid\ActionColumn',
                                            'template' => '{status_change} {view} {update} {delete}',
                                            'buttons' => [
                                                'status_change' => function ($url,$model) {
                                                    return Html::a(
                                                        ($model->status==1) ? '<i class="fa fa-dot-circle-o text-success">&nbsp;</i>' : '<i class="fa fa-dot-circle-o text-red">&nbsp;</i>',
                                                        ['celebrity/status', 'id' => $model->id, 'prevState'=>$model->status],  
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

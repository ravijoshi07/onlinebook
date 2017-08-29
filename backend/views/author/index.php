<?php

use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Author Management';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="slider-index">

    
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                
                <div class="box-header with-border">
                    <h3 class="box-title">Author Listing</h3>
                    
                    <div class="box-tools pull-right">
                     <?php echo Html::a(
                                '<i class="fa fa-plus-circle">&nbsp;</i> Add', Yii::$app->urlManager->createUrl('author/create'),
                                [

                                    'title' => 'Create Author', 
                                    'class' => ' btn btn-primary btn-flat',
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

                                <?php

                                    echo GridView::widget([        
                                        'dataProvider' => $dataProvider,
                                        'filterModel' => $model,
                                        'layout' => "<div class='table-responsive'>{items}</div>\n<div><div class='pull-left pagination'>{summary}</div><div class='pull-right'>{pager}</div></div>",
                                        'columns' => [
                                            
                                            [
                                                'attribute' => 'name',
                                                'filterInputOptions' => [
                                                    'class'       => 'form-control',
                                                    'placeholder' =>   $model->getAttributeLabel("name"),
                                                 ]
                                            ],
                                            [
                                                'attribute' => 'stream',
                                                'filterInputOptions' => [
                                                    'class'       => 'form-control',
                                                    'placeholder' =>   $model->getAttributeLabel("stream"),
                                                 ]
                                            ],
                                            [
                                                'attribute' => 'created_at',
                                                'filterInputOptions' => [
                                                    'class'       => 'form-control',
                                                    'placeholder' =>   $model->getAttributeLabel("created_at"),
                                                 ]
                                            ],
                                            [
                                                'class' => 'yii\grid\ActionColumn',
                                                'template' => '{status_change} {view} {update} {delete}',
                                                'buttons' => [
                                                    'status_change' => function ($url,$model) {
                                                        return Html::a(
                                                            ($model->status==1) ? '<i class="fa fa-dot-circle-o text-success">&nbsp;</i>' : '<i class="fa fa-dot-circle-o text-red">&nbsp;</i>',
                                                            ['author/status', 'id' => $model->id, 'prevState'=>$model->status],  
                                                            [
                                                                'title' => ($model->status==1) ? 'Inactivate' : 'Activate',
                                                                'data-pjax' => '1',
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

<script type="text/javascript">
    function submitUserForm(userId){
        
        var url = "<?php echo Yii::$app->urlManager->createUrl('author/update?id='); ?>"+userId;
                
        if(parseInt(userId)==0){
            var url = "<?php echo Yii::$app->urlManager->createUrl('author/create'); ?>";
            var formData =  $('#NewUserForm').serialize()
        } else {
            var formData =  $('#UpdateUserForm').serialize();
        }
        
        $.ajax({
            url     :   url,
            type    :   "POST",
            data    :   formData,
            success :   function(retData,response){
                if(retData=='1'){
                    alertFront('1','User saved successfully');
                    $('#modal').modal('hide');
                } else if(retData=='0') {
                    alertFront('0','User cannot be saved');
                    $('#modal').modal('hide');
                } else {
                    $('#modal').find('.modal-body').html(retData);                        
                    $('#modal').modal('show');
                }
            },
            error   :   function(){
                alertFront('0','Oops! Some error occured during debiting the Wallet. Please try again later.');
            }
        });
    }

</script>
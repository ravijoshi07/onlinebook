<?php

use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Subcategories under '.$category->name.' Category';
$this->params['breadcrumbs'][] = ['label' => $category->name.' Category', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-index">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                
                <div class="box-header with-border">
                    <h3 class="box-title">Subcategory Listing</h3>
                    
                    <div class="box-tools pull-right">
                        <?php echo Html::button(
                                '<i class="fa fa-plus-circle">&nbsp;</i> Add', 
                                [
                                    'value' => Yii::$app->urlManager->createUrl('category/subcategorycreate?category_id=').$category->id,
                                    'title' => 'Create Subcategory under '.$category->name.' Category', 
                                    'class' => 'showModalButton btn btn-primary btn-flat',
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
                                        'layout' => "<div class='table-responsive'>{items}</div>\n<div class='pull-left'>{summary}</div><div class='pull-right'>{pager}</div>",
                                        'columns' => [
                                            ['class' => 'yii\grid\SerialColumn'],                                            
                                            'name',
                                            'created_at',
                                            
                                            [
                                                'class' => 'yii\grid\ActionColumn',
                                                'template' => '{status_change} {update} {delete}',
                                                'buttons' => [


                                                    'status_change' => function ($url,$model) {
                                                        return Html::a(
                                                            ($model->status==1) ? '<i class="fa fa-dot-circle-o text-success">&nbsp;</i>' : '<i class="fa fa-dot-circle-o text-red">&nbsp;</i>',
                                                            ['category/subcategorystatus', 'id' => $model->id, 'prevState'=>$model->status],  
                                                            [
                                                                'title' => ($model->status==1) ? 'Inactivate' : 'Activate',
                                                                'data-pjax' => '1',
                                                            ]
                                                        );
                                                    },

                                                    'update' => function ($url,$model) {
                                                        return Html::a(
                                                            '<span class="glyphicon glyphicon-pencil"></span>',
                                                            '#',
                                                            [
                                                                'title' => 'Update Subcategory', 
                                                                'data-pjax' => '1',
                                                                'class' => 'showModalButton',
                                                                'value' => Yii::$app->urlManager->createUrl('category/subcategoryupdate').'?id='.$model->id,
                                                            ]
                                                        );
                                                    },
                                                            
                                                ],
                                                'urlCreator' => function ($action, $model, $key, $index) {
                                                    if ($action === 'delete') {
                                                        $url = Yii::$app->urlManager->createUrl('category/subcategorydelete').'?id='.$model->id;
                                                        return $url;
                                                    }
                                                }
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
    function submitSubcategoryForm(categoryId,subcategoryId){
        
        var url = "<?php echo Yii::$app->urlManager->createUrl('category/subcategoryupdate?id='); ?>"+subcategoryId;
                
        if(parseInt(subcategoryId)==0){
            var url = "<?php echo Yii::$app->urlManager->createUrl('category/subcategorycreate?category_id=').$category->id; ?>";
            var formData =  $('#NewSubcategoryForm').serialize()
        } else {
            var formData =  $('#UpdateSubcategoryForm').serialize();
        }
        
        $.ajax({
            url     :   url,
            type    :   "POST",
            data    :   formData,
            success :   function(retData,response){
                if(retData=='1'){
                    alertFront('1','Subcategory saved successfully');
                    $('#modal').modal('hide');
                    window.location.reload();
                } else if(retData=='0') {
                    alertFront('0','Subcategory cannot be saved');
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


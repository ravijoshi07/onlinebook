<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Celebrity */

$this->title = 'Create Celebrity';
$this->params['breadcrumbs'][] = ['label' => 'Celebrities', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="celebrity-create">

    <div class="row">
        <div class="col-md-12">
            <div class="box">
                
                <div class="box-header with-border">
                    <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
                    
                    <div class="box-tools pull-right">
                        <?php  ?>
                    </div>
                    
                </div>
                
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">               
                            <?= $this->render('_form', [
                                'model' => $model,
                            ]) ?>
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

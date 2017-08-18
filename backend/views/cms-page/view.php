<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\CmsPage */

$this->title = 'View CMS Page';
$this->params['breadcrumbs'][] = ['label' => 'Cms Pages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cms-page-view">

    <div class="row">
        <div class="col-md-12">
            <div class="box">

                <div class="box-header with-border">
                    <h3 class="box-title"><?= Html::encode($model->title) ?></h3>

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
                                    'title',
                                    'unique_name',
                                    'content:html',
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

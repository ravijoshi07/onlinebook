<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Celebrity */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="celebrity-form">

    <div class="box-body">
        <div class="col-md-12">
            <?php $form = ActiveForm::begin([
                'options' => [
                    'class' => 'form-horizontal',
                    'enctype' => 'multipart/form-data',
                    
                    'enableClientScript' => false,
                    'enableClientValidation'=>false,
                    'enableAjaxValidation' => false,
                    'clientOptions'=>[
                        'validateOnSubmit' => false
                    ],
                ],
            ]); ?>

            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'image')->fileInput(['accept' => 'image/*']) ?>

            <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

            <?= $form->field($model, 'product_id')->dropDownList(['1' => 'Product A', '2' => 'Product B', '3' => 'Product C']); ?>

            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                <?php echo Html::a(
                        'Cancel', 
                        Yii::$app->urlManager->createUrl('celebrity/index'),
                        [
                            'title' => 'Cancel', 
                            'class' => 'btn btn-default',
                            'id'=>'createBtn'
                        ]
                    ); 
                ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

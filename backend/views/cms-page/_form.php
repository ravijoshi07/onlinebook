<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model app\models\CmsPage */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cms-page-form">

    <?php $form = ActiveForm::begin([
        'options' => ['class' => 'form-horizontal',
        'enctype' => 'multipart/form-data']
    ]); ?>
    
    <div class="box-body">
        <div class="col-md-12">
            <?php
                if($model->isNewRecord){
                    echo $form->field($model, 'unique_name')->textInput(['maxlength' => true]);                    
                } 
            ?>

            <?php echo $form->field($model, 'title')->textInput(['maxlength' => true]); ?>

            <?php 
                //echo $form->field($model, 'content')->textarea(['rows' => 6]);                    
                echo $form->field($model, 'content')->widget(CKEditor::className(), [
                    'options' => ['rows' => 6],
                    'preset' => 'standard',
                    // removed image upload functionality only link to give
                ]);
            ?>

            <div class="form-group">
                <?php echo Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>

                <?php echo Html::a(
                        'Cancel', 
                        Yii::$app->urlManager->createUrl('cms-page/index'),
                        [
                            'title' => 'Cancel', 
                            'class' => 'btn btn-default btn-flat',
                            'id'=>'createBtn'
                        ]
                    ); 
                ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>

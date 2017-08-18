<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Subcategory */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="category-form">

    <?php $form = ActiveForm::begin([
           'options' => [
               'id' => (!empty($model->id)) ? 'UpdateSubcategoryForm' : 'NewSubcategoryForm',
               'enableClientScript' => true,
               'enableClientValidation'=>true,
               'enableAjaxValidation' => true,
               'clientOptions'=>[
                   'validateOnSubmit' => true
               ],
               'htmlOptions' => [
                   'enctype' => 'multipart/form-data', 
                   'class'=>'form-horizontal form-label-left',
                   'onsubmit'=>'return false;'
               ]
           ] 
        ]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->radioList([0=>'Inactive',1=>'Active'],['class' => 'i-checks']); ?>

    <div class="form-group">
        
        <?php echo Html::button($model->isNewRecord ? 'Create' : 'Update', 
                [
                    'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary',
                    'type'=>'button',
                    'onclick'=> $model->isNewRecord ? "submitSubcategoryForm($category_id,0)" : "submitSubcategoryForm($category_id,$model->id)"
                ]
            )
        ?>
        
    </div>

    <?php ActiveForm::end(); ?>

</div>

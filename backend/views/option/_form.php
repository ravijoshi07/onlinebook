<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Option */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Create Option';
$this->params['breadcrumbs'][] = ['label' => 'Options', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="option-form">

    <?php 
        $form = ActiveForm::begin([
           'options' => [
               'id' => (!empty($model->id)) ? 'UpdateOptionForm' : 'NewOptionForm',
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
        ]); 
    ?>

    <?php echo $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'unit')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->radioList([0=>'Inactive',1=>'Active'],['class' => 'i-checks']); ?>
    
    <div class="form-group">
        <?php echo Html::button($model->isNewRecord ? 'Create' : 'Update', 
                [
                    'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary',
                    'type'=>'button',
                    'onclick'=> $model->isNewRecord ? "submitOptionForm(0)" : "submitOptionForm($model->id)"
                ]
            )
        ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
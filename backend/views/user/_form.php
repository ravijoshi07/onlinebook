<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Create User';
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="user-form">

    <?php 
        $form = ActiveForm::begin([
           'options' => [
               'id' => (!empty($model->id)) ? 'UpdateUserForm' : 'NewUserForm',
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

    <?php echo $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'password_hash')->passwordInput(['maxlength' => true]) ?>
    
    <?php echo $form->field($model, 'confirm_password')->passwordInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'email')->textInput(['maxlength' => true]) ?>    

    <div class="form-group">
        <?php echo Html::button($model->isNewRecord ? 'Create' : 'Update', 
                [
                    'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary',
                    'type'=>'button',
                    'onclick'=> $model->isNewRecord ? "submitUserForm(0)" : "submitUserForm($model->id)"
                ]
            )
        ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
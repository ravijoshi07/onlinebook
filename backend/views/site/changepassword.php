<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\web\View;

$this->title = 'Change Password';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <!-- /.col-lg-12 -->
    </div>
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <?php echo $this->title;?>
            </div>
            <div class="panel-body">
                <?php $form = ActiveForm::begin([
                    'id' => 'form-signup',
                    'layout' => 'horizontal',
                    'enableClientValidation'=>false,
                    'fieldConfig' => [
                        //'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
                        'horizontalCssClasses' => [
                            'label' => 'col-sm-3',
                            'offset' => 'col-sm-offset-3',
                            'wrapper' => 'col-sm-4',
                            'error' => '',
                            'hint' => '',
                        ],
                    ],
                    'options'=>['enctype' => 'multipart/form-data']
                ]); ?>                
                <?= $form->field($model, 'old_password',[
                    'inputOptions' => [
                        'placeholder' => $model->getAttributeLabel('old_password'),
                    ],
                ])->passwordInput();?>
                <?= $form->field($model, 'new_password',[
                    'inputOptions' => [
                        'placeholder' => $model->getAttributeLabel('new_password'),
                    ],
                ])->passwordInput();?>
                <?= $form->field($model, 'confirm_new_password',[
                    'inputOptions' => [
                        'placeholder' => $model->getAttributeLabel('confirm_new_password'),
                    ],
                ])->passwordInput();?>                
                <div class="form-group">
                    <div class="col-lg-offset-3 col-lg-9">
                        <?= Html::submitButton('Update', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                        <?= Html::Button('Reset', ['type'=>'reset','class' => 'btn btn-default']) ?>
                    </div>
                </div>                          
                <?php ActiveForm::end(); ?>
            </div>            
        </div>
    </div>    
</div>
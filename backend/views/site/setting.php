<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use common\components\Fn;
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

$this->title = 'Setting';
$this->params['breadcrumbs'][] = $this->title;


/*use kartik\editable\Editable;

echo Editable::widget([
    'model'=>$model, 
    'attribute' => 'u_firstname',
    'type'=>'primary',
    'size'=>'sm',
    'inputType'=>Editable::INPUT_TEXT,
    'editableValueOptions'=>['class'=>'text-success h3']
]);*/

?>
<div class="row">
    <!-- /.col-lg-12 -->
    </div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
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
                    <?php foreach($list as $lists){  ?>
                    <?php if($lists->input_type=='text'){ 
                        $errorClass='';
                    ?>

                    <?php if(isset($model->errors[$lists->key][0])){
                            $errorClass='has-error';
                          } 
                    ?>
                    <div class="row">
                        <div class="form-group <?php echo $errorClass; ?>">
                             <?= Html::label($lists->label, 'username', ['class' => 'control-label col-sm-3']) ?>   
                            <div class="col-sm-4">
                            <?= Html::textInput('Setting['.$lists->key.']', $lists->value,['class'=>'form-control']) ?>
                            <?php if(isset($model->errors[$lists->key][0])){ ?>
                            <div class="help-block help-block-error "><?php echo $model->errors[$lists->key][0]; ?></div>
                            <?php } ?>
                            </div>
                        </div>
                    </div>
                    <?php } elseif ($lists->input_type=='file') { ?>
                        <?php echo Html::label($lists->label, 'username', ['class' => 'control-label col-sm-3']) ?>   
                        <div class="col-sm-4">
                        <?php echo Html::fileInput('Setting['.$lists->key.']', $lists->value,['class'=>'form-control','accept' => 'application/pdf']) ?>
                        <?php if(isset($model->errors[$lists->key][0])){ ?>
                            <div class="help-block help-block-error "><?php echo $model->errors[$lists->key][0]; ?></div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                    <?php 
                    } ?>           
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

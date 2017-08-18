<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use common\components\Fn;
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

$this->title = 'Profile';
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
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <?php echo $this->title;?>
            </div>
            <div class="panel-body">
                <?php $form = ActiveForm::begin([
                    'id' => 'form-signup',
                    'layout' => 'horizontal',
                    'enableClientValidation'=>true,
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
                <?php echo $form->field($model, 'first_name',[
                    'inputOptions' => [
                        'placeholder' => $model->getAttributeLabel('first_name'),
                    ],
                ]) ?>
                <?php echo $form->field($model, 'last_name',[
                    'inputOptions' => [
                        'placeholder' => $model->getAttributeLabel('last_name'),
                    ],
                ]) ?>
                <?php echo $form->field($model, 'email',[
                    'inputOptions' => [
                        'placeholder' => $model->getAttributeLabel('email'),
                    ],
                ]) ?> 
                <?php echo $form->field($model, 'image')->fileInput(array('accept' => 'image/*')) ?>
                <div class="form-group">
                    <div class="col-lg-offset-3 col-lg-9">
                        <?php echo Html::submitButton('Update', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                        <?php echo Html::Button('Reset', ['type'=>'reset','class' => 'btn btn-default']) ?>
                    </div>
                </div>                          
                <?php ActiveForm::end(); ?>
            </div>            
        </div>
    </div>    
</div>
<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use common\components\Fn;
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */
if(!isset($model->id)){
    $this->title = 'Create Author';
}else{
    $this->title = 'Update Author';   
}

$this->params['breadcrumbs'][] = $this->title;
// $this->params['breadcrumbs'][] = [
//                                     'label' => $this->title, 
//                                     'template' => "><li class='active' style='float: right;''><i class='fa fa-sliders'></i> {link}</li>"
//                                  ];


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
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?php echo $this->title;?>
            </div>
            <div class="panel-body">
                <?php $form = ActiveForm::begin([
                    'id' => 'form-signup',
                    'layout' => 'horizontal',
                    'enableClientValidation'=>true,
                    'fieldConfig' => [
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
                <?php echo $form->field($model, 'name',[
                    'inputOptions' => [
                        'placeholder' => $model->getAttributeLabel('name'),
                    ],
                ]) ?>
                <?php echo $form->field($model, 'stream',[
                    'inputOptions' => [
                        'placeholder' => $model->getAttributeLabel('stream'),
                    ],
                ]) ?>
                <div class="form-group">
                    <div class="col-lg-offset-3 col-lg-9">
                        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                        <?= Html::Button('Reset', ['type'=>'reset','class' => 'btn btn-default']) ?>
                    </div>
                </div>                          
                <?php ActiveForm::end(); ?>
            </div>            
        </div>
    </div>    
</div>
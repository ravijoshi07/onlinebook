<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = 'Reset Password';
$fieldOptions2 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-lock form-control-feedback'></span>"
];
?>
<?php //echo "<pre>";print_r($model);exit; ?>
<div class="login-box">
    <div class="login-logo">
        <a href="#"><b><?php echo Yii::$app->params['site_name'] ?></b></a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <div class="site-reset-password">
            <h1><?= Html::encode($this->title) ?></h1>

            <p>Please choose your new password:</p>
            <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>

                <?= $form->field($model, 'password',$fieldOptions2)->passwordInput(['autofocus' => true]) ?>

                <div class="form-group">
                    <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
    <!-- /.login-box-body -->
</div><!-- /.login-box -->

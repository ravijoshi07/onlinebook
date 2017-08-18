<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

AppAsset::register($this);

$this->registerCssFile("@web/css/custom.css");
$this->registerCssFile("@web/css/jquery-ui.css");
$this->registerCssFile("@web/css/fonts/font-awesome.min.css");

$this->registerCssFile("@web/css/jp_admin.css");

$this->beginPage() ?>

<!DOCTYPE html>
<html lang="<?php echo Yii::$app->language ?>">
<head>
    <meta charset="<?php echo Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php echo Html::csrfMetaTags() ?>
    
    <title><?php echo Html::encode($this->title) ?></title>
    
        <?php $this->head() ?>    
        
        <script type="text/javascript">
            $(document)
                .ajaxStart(
                    function () {
                        $.blockUI(
                            {
                                message: 'Image tag with url of image; Loading ... ',
                                overlayCSS: { backgroundColor: '#FFFFFF'}
                            }
                        )
                    }
                )
                .ajaxStop($.unblockUI);
            
        </script>

    </head>
    
    


<!--              Noew Code Here   -->

    <body class="hold-transition skin-blue sidebar-mini">
        <!-- Site wrapper -->
        <div class="wrapper">

            <?php $this->beginContent('@app/views/layouts/flash.php');  $this->endContent(); ?>

            
            <?php $this->beginContent('@app/views/layouts/leftmenu.php');$this->endContent(); ?>

            <div class="content-wrapper">
                
                <section class="content-header">
                    <h1> 
                        Blank page
                        <small>it all starts here</small>
                    </h1>
                    <!--<ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="#">Examples</a></li>
                        <li class="active">Blank page</li>
                    </ol>-->
                    
                    <?php echo Breadcrumbs::widget([
                        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                    ]) ?>
                    <?php echo Alert::widget() ?>
                </section>
                
                <section class="content">
                    <?php echo $content; ?>
                </section>
                
            </div>
            
            <footer class="main-footer">
                <div class="pull-right hidden-xs">
                    <b>Version</b> 1.0.0
                </div>
                <strong>Copyright &copy; 2014-2016 <?php echo Yii::$app->params['site_name'] ?>.</strong> All rights reserved.
            </footer>
        </div>
    </body>
</html>

<?php $this->endPage() ?>
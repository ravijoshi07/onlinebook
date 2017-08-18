<?php

use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */


if (Yii::$app->controller->action->id === 'login') {
    /**
     * Do not use this code in your template. Remove it. 
     * Instead, use the code  $this->layout = '//main-login'; in your controller.
     */
    backend\assets\AppAsset::register($this);
    echo $this->render(
            'main-login', ['content' => $content]
    );
} else {

    if (class_exists('backend\assets\AppAsset')) {
        backend\assets\AppAsset::register($this);
    } else {
        app\assets\AppAsset::register($this);
    }

    dmstr\web\AdminLteAsset::register($this);

    $directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');
    
    ?>

    <?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?php echo Yii::$app->language ?>">
        <head>
            <meta charset="<?php echo Yii::$app->charset ?>"/>
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <?php echo Html::csrfMetaTags() ?>
            <title><?php echo Html::encode($this->title) ?></title>
            <?php $this->head() ?>
        </head>
        <body class="hold-transition skin-blue sidebar-mini">
            <?php $this->beginBody() ?>
            <div class="wrapper">

                <?php echo $this->render('header.php', ['directoryAsset' => $directoryAsset]); ?>

                <?php echo $this->render('left.php', ['directoryAsset' => $directoryAsset]); ?>

                <?php echo $this->render('content.php', ['content' => $content, 'directoryAsset' => $directoryAsset]); ?>

            </div>

            <?php $this->endBody() ?>
        </body>
    </html>
    <?php $this->endPage() ?>
    
    <script type="text/javascript">
        /*
         * To Show Modal Box if showModalButton is hit
         */
        $(document).on('click', '.showModalButton', function(){
            //check if the modal is open. if it's open just reload content not whole modal
            //also this allows you to nest buttons inside of modals to reload the content it is in
            //the if else are intentionally separated instead of put into a function to get the 
            //button since it is using a class not an #id so there are many of them and we need
            //to ensure we get the right button and content. 
            if ($('#modal').data('bs.modal').isShown) { 
                $('#modal').find('.modal-body')
                        .load($(this).attr('value'));
            } else {
                //if modal isn't open; open it and load content
                $('#modal').modal('show')
                        .find('.modal-body')
                        .load($(this).attr('value'));
            }
            //dynamiclly set the header for the modal via title tag
            $('#modalHeader').find('.modalTitle').remove();
            $('#modalHeader').append('<h4 class="modalTitle">' + $(this).attr('title') + '</h4>');
        });
        
        
        function alertFront(key,msg){
            $('.alert').remove();
            if(key==0){
                //error
                var AlertHtml   =   '<div id="w1-error" class="alert-danger alert fade in"><button class="close" type="button" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-ban"></i>'+msg+'</div>';
            } else {
                //success
                var AlertHtml   =   '<div id="w1-success" class="alert-success alert fade in"><button class="close" type="button" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-check"></i>'+msg+'</div>';                
            }
            $('.content').prepend(AlertHtml);
        }
        
    </script>
    
<?php } ?>

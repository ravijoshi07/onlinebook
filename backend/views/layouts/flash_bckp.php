<?php
foreach (Yii::$app->session->getAllFlashes() as $key => $message) {

    if ($key == 'error') {
        $k = 'Error';
        $l = 'fa-thumbs-o-down';
    } else {
        $k = 'Success';
        $l = 'fa-thumbs-o-up';
    }
    ?>   

    <div class="ui-pnotify">
        <div class="alert ui-pnotify-container alert-dark ui-pnotify-shadow">

            <div class="ui-pnotify-closer" style="cursor: pointer;" onclick="$('.ui-pnotify').fadeOut(500);">
                <span class="fa fa-times" title="Close"></span>
            </div>

            <div class="ui-pnotify-icon">
                <span class="fa fa-lg <?php echo $l; ?>"></span>
            </div>
            <h4 class="ui-pnotify-title"><?php echo $k; ?></h4>
            <div class="ui-pnotify-text"><?php echo $message; ?></div>
        </div>
    </div>
    <?php
}
?>
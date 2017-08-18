<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CmsPage */

$this->title = 'Update Cms Page: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Cms Pages', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="cms-page-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

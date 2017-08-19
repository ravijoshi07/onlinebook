<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <?php echo Html::img('@web/uploads/admin/'.Yii::$app->user->identity->image, ['class' => 'img-circle']); ?>
            </div>
            <div class="pull-left info">

                <p><?php echo Yii::$app->user->identity->first_name.' '.Yii::$app->user->identity->last_name; ?></p>


                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        <?php /* ?>
        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
                <span class="input-group-btn">
                    <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                    </button>
                </span>
            </div>
        </form>
        <!-- /.search form -->
        <?php */ ?>
        <?php echo dmstr\widgets\Menu::widget(
                [
                    'options' => ['class' => 'sidebar-menu'],
                    'items' => [
                        //['label' => 'Menu Yii2', 'options' => ['class' => 'header']],
                        ['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii']],
                        //['label' => 'Debug', 'icon' => 'dashboard', 'url' => ['/debug']],
                        ['label' => 'Dashboard', 'icon' => 'dashboard', 'url' => ['/site']],
                        ['label' => 'Manage User', 'icon' => 'users', 'url' => ['/user']],
                        ['label' => 'Manage Cms Pages', 'icon' => 'pagelines', 'url' => ['/cms-page']],
                        ['label' => 'Manage Celebrities', 'icon' => 'star', 'url' => ['/celebrity']],
                        ['label' => 'Manage Locations', 'icon' => 'map-marker', 'url' => ['/country']],
                        [
                            'label' => 'Product Management',
                            'icon' => 'pie-chart',
                            'url' => '#',
                            'items' => [
                                ['label' => 'Categories', 'icon' => 'sitemap', 'url' => ['/category'],],
                                ['label' => 'Options', 'icon' => 'map-signs', 'url' => ['/option'],]
                            ],
                        ],
                        ['label' => 'Manage Slider', 'icon' => 'sliders', 'url' => ['/slider']],
                        ['label' => 'Manage Author', 'icon' => 'users', 'url' => ['/author']],
                        
                        //['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
                        /*[
                            'label' => 'Same tools',
                            'icon' => 'share',
                            'url' => '#',
                            'items' => [
                                ['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii'],],                                
                                ['label' => 'Debug', 'icon' => 'dashboard', 'url' => ['/debug'],],
                                [
                                    'label' => 'Level One',
                                    'icon' => 'circle-o',
                                    'url' => '#',
                                    'items' => [
                                        ['label' => 'Level Two', 'icon' => 'circle-o', 'url' => '#',],
                                        [
                                            'label' => 'Level Two',
                                            'icon' => 'circle-o',
                                            'url' => '#',
                                            'items' => [
                                                ['label' => 'Level Three', 'icon' => 'circle-o', 'url' => '#',],
                                                ['label' => 'Level Three', 'icon' => 'circle-o', 'url' => '#',],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],*/
                    ],
                ]
        )
        ?>

    </section>

</aside>

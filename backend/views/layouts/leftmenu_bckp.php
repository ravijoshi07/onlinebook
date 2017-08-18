<?php if (!Yii::$app->user->isGuest) { ?>

    <header class="main-header">
        <!-- Logo -->
        <a href="../../index2.html" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>A</b>LT</span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b>Admin</b>LTE</span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>

            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    
                    <!-- User Account: style can be found in dropdown.less -->
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="../../dist/img/user2-160x160.jpg" class="user-image" alt="User Image">
                            <span class="hidden-xs"><?php echo Yii::$app->user->identity->first_name.' '.Yii::$app->user->identity->last_name; ?></span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- User image -->
                            <li class="user-header">
                                <img src="../../dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                                <p>
                                    Sample Name
                                    <small>Member since Nov. 2012</small>
                                </p>
                            </li>
                            <!-- Menu Body -->
                            <li class="user-body">
                                <div class="row">
                                    <div class="col-xs-4 text-center">
                                        <a href="#">Followers</a>
                                    </div>
                                    <div class="col-xs-4 text-center">
                                        <a href="#">Sales</a>
                                    </div>
                                    <div class="col-xs-4 text-center">
                                        <a href="#">Friends</a>
                                    </div>
                                </div>
                                <!-- /.row -->
                            </li>
                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <div class="pull-left">
                                    <a href="<?php echo Yii::$app->urlManager->createAbsoluteUrl(['user/profile']); ?>" class="btn btn-default btn-flat">
                                        Profile
                                    </a>
                                </div>
                                <div class="pull-right">
                                    <a href="<?php echo Yii::$app->urlManager->createAbsoluteUrl(['user/logout']); ?>" class="btn btn-default btn-flat">
                                        Sign out
                                    </a>
                                </div>
                            </li>
                        </ul>
                    </li>
                    
                </ul>
            </div>
        </nav>
    </header>




    <!-- Left side column. contains the sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- Sidebar user panel -->
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="" class="img-circle" alt="User Image">
                </div>
                <div class="pull-left info">
                    <p>Sample Name</p>
                    <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                </div>
            </div>
                        
            <?php
            
                echo \yii\widgets\Menu::widget([
                    'encodeLabels' => false,
                    'items' => [
                        ['label' => '<i class="fa fa-home"></i> <span> Dashboard </span>', 'url' => ['default/index']],
                        [
                            'label' => '<i class="fa fa-user"></i> <span> Manage User</span> <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>',
                            'url' => '#',
                            'linkOptions' => ['class' => 'dropdown-toggle', 'data-toggle' => 'dropdown'],
                            'items' => [
                                ['label' => 'Admin', 'url' => ['user/manage/0']],
                                ['label' => 'Front User', 'url' => ['user/manage/1']],
                            ]
                        ],
                        ['label' => '<i class="fa fa-pagelines"></i> <span>Cms Pages</span>', 'url' => ['cmsPage/index']],
                        ['label' => '<i class="fa fa-question-circle"></i> <span>FAQ</span>', 'url' => ['faq/index']],
                        ['label' => '<i class="fa fa-gears"></i> <span>Site Settings</span>', 'url' => ['webSetting/index']],
                    ],
                    'options' => ['class' => 'sidebar-menu','data-widget'=>'tree'],
                    'activateItems' => true,
                    'activateParents' => true,
                    'submenuTemplate' => "\n<ul class='treeview-menu'>\n{items}\n</ul>\n",
                    'activeCssClass' => 'active'
                ]);
            
                
            ?>
            <?php /*
            <ul class="sidebar-menu" data-widget="tree">
                <li class="header">MAIN NAVIGATION</li>
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="../../index.html"><i class="fa fa-circle-o"></i> Dashboard v1</a></li>
                        <li><a href="../../index2.html"><i class="fa fa-circle-o"></i> Dashboard v2</a></li>
                    </ul>
                </li>
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-files-o"></i>
                        <span>Layout Options</span>
                        <span class="pull-right-container">
                            <span class="label label-primary pull-right">4</span>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="../layout/top-nav.html"><i class="fa fa-circle-o"></i> Top Navigation</a></li>
                        <li><a href="../layout/boxed.html"><i class="fa fa-circle-o"></i> Boxed</a></li>
                        <li><a href="../layout/fixed.html"><i class="fa fa-circle-o"></i> Fixed</a></li>
                        <li><a href="../layout/collapsed-sidebar.html"><i class="fa fa-circle-o"></i> Collapsed Sidebar</a></li>
                    </ul>
                </li>
                <li>
                    <a href="../widgets.html">
                        <i class="fa fa-th"></i> <span>Widgets</span>
                        <span class="pull-right-container">
                            <small class="label pull-right bg-green">Hot</small>
                        </span>
                    </a>
                </li>
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-pie-chart"></i>
                        <span>Charts</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="../charts/chartjs.html"><i class="fa fa-circle-o"></i> ChartJS</a></li>
                        <li><a href="../charts/morris.html"><i class="fa fa-circle-o"></i> Morris</a></li>
                        <li><a href="../charts/flot.html"><i class="fa fa-circle-o"></i> Flot</a></li>
                        <li><a href="../charts/inline.html"><i class="fa fa-circle-o"></i> Inline charts</a></li>
                    </ul>
                </li>
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-laptop"></i>
                        <span>UI Elements</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="../UI/general.html"><i class="fa fa-circle-o"></i> General</a></li>
                        <li><a href="../UI/icons.html"><i class="fa fa-circle-o"></i> Icons</a></li>
                        <li><a href="../UI/buttons.html"><i class="fa fa-circle-o"></i> Buttons</a></li>
                        <li><a href="../UI/sliders.html"><i class="fa fa-circle-o"></i> Sliders</a></li>
                        <li><a href="../UI/timeline.html"><i class="fa fa-circle-o"></i> Timeline</a></li>
                        <li><a href="../UI/modals.html"><i class="fa fa-circle-o"></i> Modals</a></li>
                    </ul>
                </li>
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-edit"></i> <span>Forms</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="../forms/general.html"><i class="fa fa-circle-o"></i> General Elements</a></li>
                        <li><a href="../forms/advanced.html"><i class="fa fa-circle-o"></i> Advanced Elements</a></li>
                        <li><a href="../forms/editors.html"><i class="fa fa-circle-o"></i> Editors</a></li>
                    </ul>
                </li>
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-table"></i> <span>Tables</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="../tables/simple.html"><i class="fa fa-circle-o"></i> Simple tables</a></li>
                        <li><a href="../tables/data.html"><i class="fa fa-circle-o"></i> Data tables</a></li>
                    </ul>
                </li>
                <li>
                    <a href="../calendar.html">
                        <i class="fa fa-calendar"></i> <span>Calendar</span>
                        <span class="pull-right-container">
                            <small class="label pull-right bg-red">3</small>
                            <small class="label pull-right bg-blue">17</small>
                        </span>
                    </a>
                </li>
                <li>
                    <a href="../mailbox/mailbox.html">
                        <i class="fa fa-envelope"></i> <span>Mailbox</span>
                        <span class="pull-right-container">
                            <small class="label pull-right bg-yellow">12</small>
                            <small class="label pull-right bg-green">16</small>
                            <small class="label pull-right bg-red">5</small>
                        </span>
                    </a>
                </li>
                <li class="treeview active">
                    <a href="#">
                        <i class="fa fa-folder"></i> <span>Examples</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="invoice.html"><i class="fa fa-circle-o"></i> Invoice</a></li>
                        <li><a href="profile.html"><i class="fa fa-circle-o"></i> Profile</a></li>
                        <li><a href="login.html"><i class="fa fa-circle-o"></i> Login</a></li>
                        <li><a href="register.html"><i class="fa fa-circle-o"></i> Register</a></li>
                        <li><a href="lockscreen.html"><i class="fa fa-circle-o"></i> Lockscreen</a></li>
                        <li><a href="404.html"><i class="fa fa-circle-o"></i> 404 Error</a></li>
                        <li><a href="500.html"><i class="fa fa-circle-o"></i> 500 Error</a></li>
                        <li class="active"><a href="blank.html"><i class="fa fa-circle-o"></i> Blank Page</a></li>
                        <li><a href="pace.html"><i class="fa fa-circle-o"></i> Pace Page</a></li>
                    </ul>
                </li>
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-share"></i> <span>Multilevel</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="#"><i class="fa fa-circle-o"></i> Level One</a></li>
                        <li class="treeview">
                            <a href="#"><i class="fa fa-circle-o"></i> Level One
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="#"><i class="fa fa-circle-o"></i> Level Two</a></li>
                                <li class="treeview">
                                    <a href="#"><i class="fa fa-circle-o"></i> Level Two
                                        <span class="pull-right-container">
                                            <i class="fa fa-angle-left pull-right"></i>
                                        </span>
                                    </a>
                                    <ul class="treeview-menu">
                                        <li><a href="#"><i class="fa fa-circle-o"></i> Level Three</a></li>
                                        <li><a href="#"><i class="fa fa-circle-o"></i> Level Three</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li><a href="#"><i class="fa fa-circle-o"></i> Level One</a></li>
                    </ul>
                </li>
                <li><a href="https://adminlte.io/docs"><i class="fa fa-book"></i> <span>Documentation</span></a></li>
                <li class="header">LABELS</li>
                <li><a href="#"><i class="fa fa-circle-o text-red"></i> <span>Important</span></a></li>
                <li><a href="#"><i class="fa fa-circle-o text-yellow"></i> <span>Warning</span></a></li>
                <li><a href="#"><i class="fa fa-circle-o text-aqua"></i> <span>Information</span></a></li>
            </ul>
            
            */ ?>
        </section>
        <!-- /.sidebar -->
    </aside>




























    <?php /*

    <div class="col-md-3 left_col">
        <div class="left_col scroll-view">

            <div class="navbar nav_title" style="border: 0;">
                <a href="<?php echo Yii::$app->homeUrl; ?>" class="site_title">
                    <i class="fa fa-paw"></i>
                    <span><?php echo Yii::$app->params['site_name']; ?></span>
                </a>
            </div>
            <div class="clearfix"></div>
            <!-- menu prile quick info -->
            <div class="profile">
                <div class="profile_pic">
                    <img src="" alt="..." class="img-circle profile_img">
                </div>
                <div class="profile_info">
                    <span>Welcome,</span>
                    <h2>
                        Sample Name
                    </h2>
                </div>
            </div>
            <!-- /menu prile quick info -->
            <div class="clear"></div>

            <!-- sidebar menu -->
            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">

                <div class="menu_section">
                    <h3>&nbsp;</h3>

                    <?php
                     NavBar::begin([
                      'brandLabel' => 'My Company',
                      'brandUrl' => Yii::$app->homeUrl,
                      'options' => [
                      'class' => 'navbar-inverse navbar-fixed-top',
                      ],
                      ]); 

                    echo \yii\widgets\Menu::widget([
                        'encodeLabels' => false,
                        'items' => [
                            ['label' => '<i class="fa fa-home"></i> Dashboard', 'url' => ['default/index']],
                            [
                                'label' => '<i class="fa fa-user"></i> Manage User <span class="fa fa-chevron-down"></span>',
                                'url' => '#',
                                'linkOptions' => ['class' => 'dropdown-toggle', 'data-toggle' => 'dropdown'],
                                'items' => [
                                    ['label' => 'Admin', 'url' => ['user/manage/0']],
                                    ['label' => 'Front User', 'url' => ['user/manage/1']],
                                ]
                            ],
                            ['label' => '<i class="fa fa-pagelines"></i> Cms Pages', 'url' => ['cmsPage/index']],
                            ['label' => '<i class="fa fa-question-circle"></i> FAQ', 'url' => ['faq/index']],
                            ['label' => '<i class="fa fa-gears"></i> Site Settings', 'url' => ['webSetting/index']],
                        ],
                        'options' => ['class' => 'nav side-menu'],
                        'activateItems' => true,
                        'activateParents' => true,
                        //'submenuHtmlOptions' => ['class' => 'nav child_menu'],
                        'submenuTemplate' => "\n<ul class='nav child_menu'>\n{items}\n</ul>\n",
                        'activeCssClass' => 'nv active'
                    ]);

                    //NavBar::end();
                    ?>

                </div>


            </div>
            <!-- /sidebar menu -->
        </div>
    </div>

    <!-- top navigation -->
    <div class="top_nav">

        <div class="nav_menu">
            <nav class="" role="navigation">
                <div class="nav toggle">
                    <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                </div>

                <ul class="nav navbar-nav navbar-right">
                    <li class="">
                        <a href="#" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <img src="" alt="..." >
                            Sample Name
                            <span class=" fa fa-angle-down"></span>
                        </a>
                        <ul class="dropdown-menu dropdown-usermenu animated fadeInDown pull-right">
                            <li>
                                <a href="<?php echo Yii::$app->urlManager->createAbsoluteUrl(['user/profile']); ?>">
                                    <i class="fa fa-user pull-right"></i> Profile
                                </a>
                            </li>

                            <!-- <li>
                                <a href="<?php echo Yii::$app->urlManager->createAbsoluteUrl(['webSetting/index']); ?>">                                
                                    <i class="fa fa-gears pull-right"></i> Settings
                                </a>
                            </li> -->

                            <li>
                                <a href="<?php echo Yii::$app->urlManager->createAbsoluteUrl(['user/logout']); ?>">
                                    <i class="fa fa-sign-out pull-right"></i> Log Out
                                </a>
                            </li>

                        </ul>
                    </li>                    
                </ul>
            </nav>
        </div>

    </div>
    
    */ ?>
    
    <?php
} else {
    
}
?>
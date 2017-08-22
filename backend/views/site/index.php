<?php
use yii\helpers\Html;
?>
<!-- Main content -->
<section class="content">
    <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-4 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3><?php echo $totalUser;?></h3>

              <p>Users</p>
            </div>
            <div class="icon">
              <i class="fa fa-users"></i>
            </div>
            <?php echo
            Html::a(
                    'More info', ['/user'], ['class' => 'small-box-footer']
            )
            ?>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-4 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3><?php echo $totalPublication;?></h3>

              <p>Publication</p>
            </div>
            <div class="icon">
              <i class="fa fa-users"></i>
            </div>
            <?php echo
            Html::a(
                    'More info', ['/publication'], ['class' => 'small-box-footer']
            )
            ?>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-4 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3><?php echo $totalAuthor;?></h3>

              <p>Author</p>
            </div>
            <div class="icon">
              <i class="fa fa-users"></i>
            </div>
            <?php echo
            Html::a(
                    'More info', ['/author'], ['class' => 'small-box-footer']
            )
            ?>
          </div>
        </div>
        <!-- ./col -->
       </div>
      <!-- /.row -->
</section>
<!-- /.content -->
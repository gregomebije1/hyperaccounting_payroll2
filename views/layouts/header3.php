<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;


use app\models\User;
use app\models\CivilServant;   
/* @var $this \yii\web\View */
/* @var $content string */
?>
<header class="main-header">

<?= Html::a(Yii::$app->name, Yii::$app->homeUrl, ['class' => 'logo']) ?>
<nav class="navbar navbar-static-top">
  <div class="container-fluid">
    <div class="navbar-header">
      <?= Html::a(Yii::$app->name, Yii::$app->homeUrl, ['class' => 'navbar-brand']) ?>
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
        <i class="fa fa-bars"></i>
      </button>
    </div>
    <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
      <ul class="nav navbar-nav">
      <?php 
        if (Yii::$app->user->can('admin')) {
            ?>

                <li class="active"><a href="<?=Url::toRoute("/mda")?>">MDA<span class="sr-only">(current)</span></a></li>
                <li><a href="<?=Url::toRoute("/grade-level")?>">Grade Level</a></li>
                <li><a href="<?=Url::toRoute("/expense-category")?>">Expense Category</a></li>
                <li><a href="<?=Url::toRoute("/expense-type")?>">Expense Type</a></li>
                <li><a href="<?=Url::toRoute("/mda-admin")?>">Manage MDA Admin Users</a></li>
                <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown">More <span class="caret"></span></a>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="<?=Url::toRoute("/ippis")?>">IPPIS Database</a></li>
                    <li><a href="<?=Url::toRoute("/admin/reset")?>">Reset</a></li>

                  </ul>
                </li>
        <?php 
        }
        if (Yii::$app->user->can('superMdaAdmin')) {
        ?>
            <li><a href="<?=Url::toRoute("/mda-admin")?>">Manage MDA Admin Users</a></li>
        <?php 
        }
        if(Yii::$app->user->can('mdaAdmin')) {
        ?>
            <li class="active"><a href="<?=Url::toRoute("/civil-servant")?>">Staff Management<span class="sr-only">(current)</span></a></li>
            <li><a href="<?=Url::toRoute("/expense-setup")?>">Setup Expenses</a></li>
            <li><a href="<?=Url::toRoute("/department")?>"><span class="fa fa-dashboard"></span> Department</a></li>
        <?php 
        }
        $civilServant = CivilServant::find()->where(['user_id' => Yii::$app->user->id])->one();
        if ($civilServant !== NULL) {
            
            $civilServant2 = CivilServant::find()->where(['supervisor_id' => $civilServant->id])->one();
             if ($civilServant2 !== NULL) {
                ?>
                <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown">Reports <span class="caret"></span></a>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="<?=Url::toRoute("/expense-report/pending-approvals")?>">Pending Approvals</a></li>
                    <li><a href="<?=Url::toRoute("/expense-report/approved-list")?>">Approved List</a></li>
                    <li><a href="<?=Url::toRoute("/expense-report/not-approved-list")?>">Not Approved List</a></li>

                  </ul>
                </li>
            <?php 
             }
        }
        if (Yii::$app->user->can('employee')) {
        ?>
            <li><a href="<?=Url::toRoute("/expense-report")?>">Expenses</a></li>
        <?php 
        }
        ?>
     </ul>
    </div>
    <!-- /.navbar-collapse -->
    <!-- Navbar Right Menu -->
    <div class="navbar-custom-menu">
      <ul class="nav navbar-nav">
        <!-- Messages: style can be found in dropdown.less-->
        <li class="dropdown messages-menu">
          <!-- Menu toggle button -->
          
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <?php 
            if (!Yii::$app->user->can("admin")){
                $user = User::find()->where(['id' => Yii::$app->user->id])->one();
                echo "<span class='hidden-xs'>{$user->mda->name}</span>";
            }
            ?>
            <!--
            <i class="fa fa-envelope-o"></i>
            <span class="label label-success">4</span>
            -->
          </a>
          <!--
          <ul class="dropdown-menu">
            <li class="header">You have 4 messages</li>
            <li>
              <ul class="menu">
                <li>
                  <a href="#">
                    <div class="pull-left">
                      <img src="../../dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                    </div>
                    <h4>
                      Support Team
                      <small><i class="fa fa-clock-o"></i> 5 mins</small>
                    </h4>
                    <p>Why not buy a new awesome theme?</p>
                  </a>
                </li>
              </ul>
              
            </li>
            <li class="footer"><a href="#">See All Messages</a></li>
          </ul>
        </li>
          -->
        <!-- /.messages-menu -->

        <!-- Notifications Menu -->
        <li class="dropdown notifications-menu">
          <!-- Menu toggle button -->
          <!--
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-bell-o"></i>
            <span class="label label-warning">10</span>
          </a>
          -->
          <ul class="dropdown-menu">
            <li class="header">You have 10 notifications</li>
            <li>
              <!-- Inner Menu: contains the notifications -->
              <ul class="menu">
                <li><!-- start notification -->
                  <a href="#">
                    <i class="fa fa-users text-aqua"></i> 5 new members joined today
                  </a>
                </li>
                <!-- end notification -->
              </ul>
            </li>
            <li class="footer"><a href="#">View all</a></li>
          </ul>
        </li>
        <!-- Tasks Menu -->
        <li class="dropdown tasks-menu">
          <!-- Menu Toggle Button -->
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
           <!--
            <i class="fa fa-flag-o"></i>
            <span class="label label-danger">9</span>
           -->
          </a>
          <ul class="dropdown-menu">
            <li class="header">You have 9 tasks</li>
            <li>
              <!-- Inner menu: contains the tasks -->
              <ul class="menu">
                <li><!-- Task item -->
                  <a href="#">
                    <!-- Task title and progress text -->
                    <h3>
                      Design some buttons
                      <small class="pull-right">20%</small>
                    </h3>
                    <!-- The progress bar -->
                    <div class="progress xs">
                      <!-- Change the css width attribute to simulate progress -->
                      <div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                        <span class="sr-only">20% Complete</span>
                      </div>
                    </div>
                  </a>
                </li>
                <!-- end task item -->
              </ul>
            </li>
            <li class="footer">
              <a href="#">View all tasks</a>
            </li>
          </ul>
        </li>
        <!-- User Account Menu -->
        <li class="dropdown user user-menu">
          <!-- Menu Toggle Button -->
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <!-- The user image in the navbar-->
            <!--<img src="../../dist/img/user2-160x160.jpg" class="user-image" alt="User Image">-->
            <!-- hidden-xs hides the username on small devices so only the image appears. -->
            <span class="hidden-xs"><?=User::findOne(Yii::$app->user->id)->username?></span>
          </a>
          <ul class="dropdown-menu">
            <!-- The user image in the menu -->
            <li class="user-header">
              <img src="../../dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">

              <p>
                <?=User::findOne(Yii::$app->user->id)->username?>
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
                <a href="#" class="btn btn-default btn-flat">Profile</a>
              </div>
              <div class="pull-right">
                <?= Html::a(
                                    'Sign out',
                                    ['/site/logout'],
                                    ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                                ) ?>
              </div>
            </li>
          </ul>
        </li>
      </ul>
    </div>
    <!-- /.navbar-custom-menu -->
  </div>
  <!-- /.container-fluid -->
</nav>
</header>



<?php
use yii\bootstrap\Nav;
?>
<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <!--
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p>Alexander Pierce</p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        -->
        <!-- /.search form -->

        <?php 
        if (Yii::$app->user->can('admin')) {
            echo Nav::widget(
                    [
                        'encodeLabels' => false,
                        'options' => ['class' => 'sidebar-menu tree', 'data-width' => 'tree'],
                        'items' => [
                            '<li class="header">Employee</li>',
                            ['label' => '<span class="fa fa-institution"></span>Employee', 
                                'url' => ['/employee']],                           
                            ['label' => '<span class="fa fa-dashboard"></span>Payslip', 
                                'url' => ['/report/payslip']],
                            
                            '<li class="header">Payroll</li>',
                            ['label' => '<span class="fa fa-institution"></span>Process payroll', 
                                'url' => ['/payroll/create']],                           
                            ['label' => '<span class="fa fa-dashboard"></span>Payroll report', 
                                'url' => ['/report/payroll-report']],
                            ['label' => '<span class="fa fa-folder"></span>Bank schedule', 
                                'url' => ['/report/bank-schedule']],
                            ['label' => '<span class="fa fa-folder"></span>Deductions Report', 
                                'url' => ['/report/deductions-report']],
                            ['label' => '<span class="fa fa-folder"></span>Upload payroll', 
                                'url' => ['/report/upload-payroll']],
                            
                             '<li class="header">Administration</li>',                         
                            ['label' => '<span class="fa fa-dashboard"></span>Deductions/Allowances', 
                                'url' => ['/deductions-allowances']],
                             ['label' => '<span class="fa fa-dashboard"></span>Org Info', 
                                'url' => ['/entity/view?id='. Yii::$app->session['entity_id']]],
                            ['label' => '<span class="fa fa-folder"></span>Payee Item', 
                                'url' => ['/payee-item']],
                            ['label' => '<span class="fa fa-folder"></span>Bank', 
                                'url' => ['/bank']],
                            ['label' => '<span class="fa fa-folder"></span>Department', 
                                'url' => ['/department']],
                            ['label' => '<span class="fa fa-folder"></span>Grade Level', 
                                'url' => ['/grade-level']],
                            ['label' => '<span class="fa fa-folder"></span>Branch', 
                                'url' => ['/branch']],
                            ['label' => '<span class="fa fa-folder"></span>Location', 
                                'url' => ['/location']],
                            ['label' => '<span class="fa fa-folder"></span>Users', 
                                'url' => ['#']],
                        ],
                    ]
                );
            
        }
        ?>
        <!--
        <ul class="sidebar-menu">
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-share"></i> <span>Same tools</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?= \yii\helpers\Url::to(['/gii']) ?>"><span class="fa fa-file-code-o"></span> Gii</a>
                    </li>
                    <li><a href="<?= \yii\helpers\Url::to(['/debug']) ?>"><span class="fa fa-dashboard"></span> Debug</a>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-circle-o"></i> Level One <i class="fa fa-angle-left pull-right"></i></a>
                        <ul class="treeview-menu">
                            <li><a href="#"><i class="fa fa-circle-o"></i> Level Two</a></li>
                            <li>
                                <a href="#">
                                    <i class="fa fa-circle-o"></i> Level Two <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    <li><a href="#"><i class="fa fa-circle-o"></i> Level Three</a></li>
                                    <li><a href="#"><i class="fa fa-circle-o"></i> Level Three</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
        </ul>
        -->
    </section>

</aside>

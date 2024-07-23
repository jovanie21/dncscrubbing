<div class="topbar">
    <!-- LOGO -->
    <div class="topbar-left">
        <!-- <a href="#" class="logo"><span>DN<span>C</span></span><i class="mdi mdi-layers"></i></a> -->
        <?php
            $check = env('APP_TYPE', '');
        ?>
        <?php if($check == 'DNC'): ?>
            <a href="#" class="logo">
                <span>
                    <img src="<?php echo e(asset('theme/default/assets/images/logo.png')); ?>" alt="" height="100%"
                        width="100%">
                </span>
                <i>
                    <img src="<?php echo e(asset('theme/default/assets/images/logo.png')); ?>" alt="" height="13"
                        width="50">
                </i>
            </a>
        <?php else: ?>
            <a href="#" class="logo">
                <span>
                    <img src="<?php echo e(asset('webtheme/img/logo.png')); ?>" alt="" height="100%" width="100%">
                </span>
                <i>
                    <img src="<?php echo e(asset('webtheme/img/logo.png')); ?>" alt="" height="13" width="50">
                </i>
            </a>
        <?php endif; ?>
    </div>
    <!-- Button mobile view to collapse sidebar menu -->
    <div class="navbar navbar-default" role="navigation">
        <div class="container">

            <!-- Navbar-left -->
            <ul class="nav navbar-nav navbar-left">
                <li>
                    <button class="button-menu-mobile open-left waves-effect">
                        <i class="mdi mdi-menu"></i>
                    </button>
                </li>
            </ul>
            <!-- Right(Notification) -->
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown user-box">
                    <a href="#" class="dropdown-toggle waves-effect user-link" data-toggle="dropdown"
                        aria-expanded="true">
                        <img src="<?php echo e(asset('theme/default/assets/images/users/user.png')); ?>" alt="user-img"
                            class="img-circle user-img">
                    </a>
                    <ul
                        class="dropdown-menu dropdown-menu-right arrow-dropdown-menu arrow-menu-right user-list notify-list">
                        <li>
                            <h5>Hi,Admin</h5>
                        </li>
                        <li><a href="<?php echo e(url('admin/profile')); ?>"><i class="ti-user m-r-5"></i> Profile</a></li>
                        <li><a href="<?php echo e(route('logout')); ?>"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i
                                    class="ti-power-off m-r-5"></i> Logout</a>
                            <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                                <?php echo csrf_field(); ?>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul> <!-- end navbar-right -->
        </div><!-- end container -->
    </div><!-- end navbar -->
</div>
<!-- Top Bar End -->
<!-- ========== Left Sidebar Start ========== -->
<div class="left side-menu">
    <div class="sidebar-inner slimscrollleft">
        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <ul>
                <li>
                    <a href="<?php echo e(url('admin/home')); ?>" class="waves-effect"><i
                            class="mdi mdi-view-dashboard"></i><span>Dashboard</span></a>
                </li>
                <li>
                    <a href="<?php echo e(url('admin/dnclist')); ?>" class="waves-effect"><i class="mdi mdi-calendar"></i><span>DNC
                            List</span></a>
                </li>
                <li>
                    <a href="<?php echo e(url('admin/uploadadmin')); ?>" class="waves-effect"><i
                            class="mdi mdi-calendar"></i><span>
                                Internal DNC Upload</span></a>
                </li>
                <li>
                    <a href="<?php echo e(url('admin/scrub-upload')); ?>" class="waves-effect"><i class="fa fa-list"></i><span>
                            DNC Scrub</span></a>
                </li>
                <!--  not required
                <li>
                    <a href="<?php echo e(url('admin/userlist')); ?>" class="waves-effect"><i class="fa fa-list"></i><span>Client DNC List</span></a>
                </li> -->
                
                
                <li>
                    <a href="<?php echo e(route('company.index')); ?>" class="waves-effect"><i class="fa fa-users"></i><span>Company
                        </span></a>
                </li>
                <!--
                  <?php
                      $check = env('APP_TYPE', '');
                  ?>
              <?php if($check == 'DNC'): ?>
<li>
                    <a href="<?php echo e(url('admin/uploadadmin')); ?>" class="waves-effect"><i class="fa fa-upload"></i><span>Upload </span></a>
                </li>
<?php else: ?>
<li>
                    <a href="<?php echo e(url('admin/scrub-upload')); ?>" class="waves-effect"><i class="fa fa-upload"></i><span>Scrub Upload </span></a>
                </li>
<?php endif; ?>

                
                <li>
                    <a href="<?php echo e(url('admin/viewcontact')); ?>" class="waves-effect"><i class="mdi mdi-map"></i><span>Contact </span></a>
                </li> -->
            </ul>
        </div>
    </div>
    <!-- Sidebar -left -->
</div>
<!-- Left Sidebar End
<?php /**PATH /var/www/dncscrubbing/resources/views/admin/layout/header.blade.php ENDPATH**/ ?>
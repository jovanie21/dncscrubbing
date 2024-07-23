<?php $__env->startSection('title','Welcome Admin'); ?>
<?php $__env->startSection('content'); ?>
<!-- ============================================================== -->
 <!-- Start right Content here -->
<!-- ============================================================== -->
<div class="content-page">
    <!-- Start content -->
    <div class="content">
        <div class="container">

            <div class="row">
				<div class="col-xs-12">
					<div class="page-title-box">
                        <h4 class="page-title">Welcome <?php echo e(strToUpper(Auth::user()->name)); ?> </h4>
                        <div class="clearfix"></div>
                    </div>
				</div>
			</div>
            <!-- end row -->
            <div class="row">
                <div class="col-xs-12">
                    <div class="card-box">
                      <div class="row">

                            <div class="col-lg-4 col-md-4">
                                <a href="<?php echo e(url('admin/user')); ?>" target="_blank">
                                <div class="card-box widget-box-two widget-two-primary">
                                    <i class="fa fa-users widget-two-icon"></i>
                                    <div class="wigdet-two-content">
                                        <p class="m-0 text-uppercase font-600 font-secondary text-overflow" title="Statistics">Total Clients</p>
                                        <h2><span data-plugin="counterup"><?php echo e($total_user); ?></span>  </h2>
                                    </div>
                                </div>
                                </a>
                            </div><!-- end col -->

                            <div class="col-lg-4 col-md-4">
                                <a href="<?php echo e(url('admin/dnclist')); ?>" target="_blank">
                                <div class="card-box widget-box-two widget-two-warning">
                                    <i class="fa fa-user widget-two-icon"></i>
                                    <div class="wigdet-two-content">
                                        <p class="m-0 text-uppercase font-600 font-secondary text-overflow" title="User This Month">Total DNC Contacts</p>
                                        <h2><span data-plugin="counterup"><?php echo e($total_contact); ?> </span>  </h2>
                                    </div>
                                </div>
                                </a>
                            </div><!-- end col -->

                            <div class="col-lg-4 col-md-4">
                                <a href="<?php echo e(url('admin/dnclist')); ?>" target="_blank">
                                <div class="card-box widget-box-two widget-two-danger">
                                    <i class="fa fa-user widget-two-icon"></i>
                                    <div class="wigdet-two-content">
                                        <p class="m-0 text-uppercase font-600 font-secondary text-overflow" title="User This Month">Total Processed Files/Total Files</p>
                                        <h2><span data-plugin="counterup"><?php echo e($total_processed_files); ?>/<?php echo e($total_files); ?> </span>  </h2>
                                    </div>
                                </div>
                                </a>
                            </div><!-- end col -->
<!-- 
                            <div class="col-lg-3 col-md-6">
                                <div class="card-box widget-box-two widget-two-danger">
                                    <i class="mdi mdi-chart-areaspline widget-two-icon"></i>
                                    <div class="wigdet-two-content">
                                        <p class="m-0 text-uppercase font-600 font-secondary text-overflow" title="Statistics">Total Federal </p>
                                        <h2><span data-plugin="counterup"><?php echo e($total_federal); ?></span>  </h2>
                                    </div>
                                </div>
                            </div> --><!-- end col -->

                            <!-- <div class="col-lg-3 col-md-6">
                                <div class="card-box widget-box-two widget-two-success">
                                    <i class="mdi mdi-account-convert widget-two-icon"></i>
                                    <div class="wigdet-two-content">
                                        <p class="m-0 text-uppercase font-600 font-secondary text-overflow" title="User Today">Total Litigator</p>
                                        <h2><span data-plugin="counterup"><?php echo e($total_litigator); ?></span>  </h2>
                                    </div>
                                </div>
                            </div> --><!-- end col -->
                           <!--  <div class="col-lg-3 col-md-6">
                                <div class="card-box widget-box-two widget-two-info">
                                    <i class="mdi mdi-layers widget-two-icon"></i>
                                    <div class="wigdet-two-content">
                                        <p class="m-0 text-uppercase font-600 font-secondary text-overflow" title="User Today">Total Internal</p>
                                        <h2><span data-plugin="counterup"><?php echo e($total_internal); ?></span>  </h2>
                                    </div>
                                </div>
                            </div> --><!-- end col -->
                            <!-- <div class="col-lg-3 col-md-6">
                                <div class="card-box widget-box-two widget-two-primary">
                                    <i class="mdi mdi-access-point-network widget-two-icon"></i>
                                    <div class="wigdet-two-content">
                                        <p class="m-0 text-uppercase font-600 font-secondary text-overflow" title="User Today">Total Wireless</p>
                                        <h2><span data-plugin="counterup"><?php echo e($total_wireless); ?></span>  </h2>
                                    </div>
                                </div>
                            </div> -->
                            <!-- end col -->

                        </div>
                        <!-- end row -->
                    </div>
                </div>
            </div>

        </div> <!-- container -->
    </div> <!-- content -->
    <?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\DNCscrubbing\resources\views/admin/home.blade.php ENDPATH**/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Coderthemes">

    <!-- App favicon -->
    <!-- App title -->
    <title><?php echo $__env->yieldContent('title'); ?></title>

    <!-- App css -->
    <link href="<?php echo e(asset('theme/default/assets/css/bootstrap.min.css')); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo e(asset('theme/default/assets/css/core.css')); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo e(asset('theme/default/assets/css/components.css')); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo e(asset('theme/default/assets/css/icons.css')); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo e(asset('theme/default/assets/css/pages.css')); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo e(asset('theme/default/assets/css/menu.css')); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo e(asset('theme/default/assets/css/responsive.css')); ?>" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="<?php echo e(asset('theme/plugins/switchery/switchery.min.css')); ?>">
    <?php echo $__env->yieldPushContent('headerscript'); ?>
</head>


<body class="fixed-left">

    <!-- Begin page -->
    <div id="wrapper">

        <?php echo $__env->make('admin.layout.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

       

        <?php echo $__env->yieldContent('content'); ?>

        
    </div>


    <!-- ============================================================== -->
    <!-- End Right content here -->
    <!-- ============================================================== -->


    <!-- Right Sidebar -->
    
</div>
<!-- END wrapper -->



<script>
    var resizefunc = [];
</script>

<!-- jQuery  -->
<script src="<?php echo e(asset('theme/default/assets/js/jquery.min.js')); ?>"></script>
<script src="<?php echo e(asset('theme/default/assets/js/bootstrap.min.js')); ?>"></script>
<script src="<?php echo e(asset('theme/default/assets/js/detect.js')); ?>"></script>
<script src="<?php echo e(asset('theme/default/assets/js/fastclick.js')); ?>"></script>
<script src="<?php echo e(asset('theme/default/assets/js/jquery.blockUI.js')); ?>"></script>
<script src="<?php echo e(asset('theme/default/assets/js/waves.js')); ?>"></script>
<script src="<?php echo e(asset('theme/default/assets/js/jquery.slimscroll.js')); ?>"></script>
<script src="<?php echo e(asset('theme/default/assets/js/jquery.scrollTo.min.js')); ?>"></script>
<script src="<?php echo e(asset('theme/plugins/switchery/switchery.min.js')); ?>"></script>

<!-- App js -->
<script src="<?php echo e(asset('theme/default/assets/js/jquery.core.js')); ?>"></script>
<script src="<?php echo e(asset('theme/default/assets/js/jquery.app.js')); ?>"></script>
<?php echo $__env->yieldPushContent('footerscript'); ?>
<?php echo $__env->make('admin.layout.notificaton', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</body>
</html><?php /**PATH D:\xampp\htdocs\DNCscrubbing\resources\views/admin/layout/app.blade.php ENDPATH**/ ?>
<?php $__env->startSection('title','Login'); ?>
<?php $__env->startSection('content'); ?>
<!-- HOME -->
<div class="video-w3l" >
    <!--header-->
    <br><br><br>
    <div class="header-w3l" style="margin-left:38% ;">
        <img src="<?php echo e(asset('webtheme/img/logo.png')); ?>" alt="logo" class="img-fluid"/>
    </div><br><br>
    <!--//header-->
    <div class="main-content-agile">
        <div class="sub-main-w3 temp text-center" style="max-width: 600px;   height: 400px; min-height: 330px; padding: 20px 20px; margin: 40px auto; box-shadow: 0px 0px 11px 13px rgba(0,0,0,0.75);">
            <h1 style="color: white;">Login Here
                <i class="fa fa-hand-o-down" aria-hidden="true"></i>
            </h1>
            <br><br>
            <form method="POST" action="<?php echo e(route('login')); ?>" class="form-horizontal form-group">
                <?php echo csrf_field(); ?>
                <div class="form-group ">
                    <div class="col-xs-1">
                        <span class="fa fa-user text-info" aria-hidden="true" style="font-size:x-large;"></span>
                    </div>
                    <div class="col-xs-11">
                        <input name="email" class="form-control" type="email" required="" placeholder="Email">
                        <div class="text-danger"><?php echo e($errors->first('email')); ?></div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-1">
                        <i class="fa fa-lock m-r-5 text-info" style="font-size:x-large;"></i>
                       <!-- <span class="fa fa-key text-info" aria-hidden="true" style="font-size:x-large;"></span> -->
                    </div>
                    <div class="col-xs-11">
                        <input name="password" id="password-field" class="form-control" type="password" required="" placeholder="Password">
                        <span toggle="#password-field" class="fa fa-fw fa-eye-slash field-icon toggle-password" style="float: right; margin-left: -25px;margin-right: 10px; margin-top: -25px; position: relative; z-index: 2; color: black "></span>
                        <div class="text-danger"><?php echo e($errors->first('password')); ?></div>
                    </div>
                </div>
                <div class="form-group ">
                    <div class="col-xs-12">
                        <div class="checkbox checkbox-success">
                            <input name="remember" id="checkbox-signup" type="checkbox" <?php echo e(old('remember') ? 'checked' : ''); ?>>
                            <label for="checkbox-signup" style="font-size: 20px; color:white">
                                Remember me
                            </label>
                        </div>

                    </div>
                </div>
		                <div class="form-group text-center ">
                    <div class="col-sm-12">
                        <a href="<?php echo e(url('/')); ?>" style=" font-size: 15px; color:white"><i class="fa fa-home"></i> Back to Home</a>
                    </div>
                </div>
                <div class="form-group account-btn text-center m-t-10">
                    <div class="col-xs-12">
                        <button class="btn w-md btn-bordered btn-default waves-effect waves-light" type="submit">Log In</button>
                    </div>
                </div>
            </form>
            <br />
            <form action="login/admin_login_reset" method="post">
                <div id="passrst1" style="display:none;">
                    <div class="pom-agile" >
                        <span class="fa fa-user-o" aria-hidden="true"></span>
                        <input placeholder="Enter Email to reset password" name="email" class="user" type="email" required="">
                    </div>
                    <div class="right-w3l">
                        <input type="submit" value="Reset">
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!--//main-->
    <!--footer-->

    <!--//footer-->
</div>

<script type="text/javascript" src="<?php echo e(asset('theme/plugins/d3/d3.min.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('theme/plugins/c3/c3.min.js')); ?>"></script>
<script src="<?php echo e(asset('theme/default/assets/pages/jquery.c3-chart.init.js')); ?>"></script>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
<script src="<?php echo e(asset('theme/plugins/select2/js/select2.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('theme/plugins/moment/moment.js')); ?>"></script>
<script type="text/javascript"src="<?php echo e(asset('theme/plugins/bootstrap-daterangepicker/daterangepicker.js')); ?>"></script>

<script type="text/javascript">
    
  $(".toggle-password").click(function() {
      $(this).toggleClass("fa-eye fa-eye-slash");
      var input = $($(this).attr("toggle"));
      if (input.attr("type") == "password") {
        input.attr("type", "text");
      } else {
        input.attr("type", "password");
      }
    });

</script>
<!-- END HOME -->
<?php $__env->stopSection(); ?>




<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/dncscrubbing/resources/views/auth/login.blade.php ENDPATH**/ ?>
<?php $__env->startSection('title','Create Client'); ?>
<?php $__env->startPush('headerscript'); ?>

<link href="<?php echo e(asset('theme/plugins/summernote/summernote.css')); ?>" rel="stylesheet" />
<style>
  .summernote{
    position: absolute;
    flex: initial;
  }
</style>
<?php $__env->stopPush(); ?>
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
            <h4 class="page-title">Create Company</h4>
            <a href="<?php echo e(route('company.index')); ?>" class="btn btn-primary btn-sm pull-right"><i class="fa fa-backward"></i> Back</a>
            <div class="clearfix"></div>
          </div>
        </div>
      </div>
      <!-- end row -->
      <div class="row">
        <div class="col-sm-12">
          <div class="card-box">
            <form method="post" action="<?php echo e(route('company.store')); ?>" class="form-validate-summernote"  enctype="multipart/form-data">
              <?php echo csrf_field(); ?>
              <div class="row form-group">
                <div class="col-sm-6">
                    <label class="">Company Name<i class="text-danger ">*</i></label>
                    <input type="company_name" name="company_name" id="company_name" value="" class="form-control" required="">
                    <div class="text-danger"><?php echo e($errors->first('company_name')); ?></div>
                  </div>  
                <div class="col-sm-6">
                  <label class="">Mobile Number<i class="text-danger ">*</i></label>
                  <input type="text" class="form-control number" name="phone" required="" minlength="10" maxlength="10" value="<?php echo e(old('phone')); ?>">
                  <div class="text-danger"><?php echo e($errors->first('phone')); ?></div>
                </div>
              </div>              
              <div class="row form-group">
                <div class="col-sm-6">
                  <label class="">Email<i class="text-danger ">*</i></label>
                  <input type="email" name="email" id="email" value="" class="form-control" required="">
                  <div class="text-danger"><?php echo e($errors->first('email')); ?></div>
                </div>  
                
                <div class="col-sm-6">
                  <label class="">Company Zip<i class="text-danger ">*</i></label>
                  <input type="company_zip" name="company_zip" id="company_zip" value="" class="form-control" required="">
                  <div class="text-danger"><?php echo e($errors->first('company_zip')); ?></div>
                </div> 
                 
             
              </div>              
            
              <div class="row form-group">
                 <div class="col-sm-6">
                  <label class="">Company Address<i class="text-danger ">*</i></label>
                  <textarea class="form-control" name="company_address"><?php echo e(old('address')); ?></textarea>
                  <div class="text-danger"><?php echo e($errors->first('company_address')); ?></div>
                </div> 
              </div>
              <div class="row form-group">
                <div class="col-sm-12">
                  <div class="col-sm-10">
                    <input type="submit" name="submit" class="btn btn-sm btn-primary" value="Submit">
                    <input type="reset" name="reset" value="Cancel" class="btn btn-sm btn-default">
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div> <!-- container -->
  </div> <!-- content -->
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('footerscript'); ?>
<script>
    function myFunction() {
        var x = document.getElementById("password");
        if (x.type === "password") {
            x.type = "text";
            $('#eye').hide();
            $('#eye_one').show();
        } else {
            x.type = "password";
            $('#eye_one').hide();
            $('#eye').show();
        }
    }
</script>

  <script>
     $('.number').keyup(function(e)
     {
        if (/\D/g.test(this.value))
        {
          this.value = this.value.replace(/\D/g, '');
      }
  });
</script>

<?php $__env->stopPush(); ?>
<?php echo $__env->make('admin.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\DNCscrubbing\resources\views/admin/company/create.blade.php ENDPATH**/ ?>
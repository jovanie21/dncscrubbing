<?php $__env->startSection('title', 'Do Not Contact'); ?>
<?php $__env->startPush('headerscript'); ?>
    <link href="<?php echo e(asset('theme/plugins/bootstrap-sweetalert/sweet-alert.css')); ?>" rel="stylesheet" type="text/css">
    <link href="<?php echo e(asset('theme/plugins/datatables/responsive.bootstrap.min.css')); ?>" rel="stylesheet" type="text/css" />
<style>
.pagination{
    float: right!important;
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
                            <h4 class="page-title">Do not Call List </h4>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <!-- end row -->
                <?php if(session()->has('success')): ?>
                    <div class="alert alert-success">
                        <?php echo e(session()->get('success')); ?>

                    </div>
                <?php endif; ?>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">
                            <h4 class="text-danger">Enter Mobile Number to check whether the number is in DNC or not</h4>
                            <form method="post" action="<?php echo e(url('admin/findnumber')); ?>" class="form-validate-summernote"
                                enctype="multipart/form-data">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="region" id="region" value="<?php echo e(request()->region); ?>">
                                <div class="row form-group">
                                    <div class="col-sm-6">
                                        <label class="">Mobile Number<i class="text-danger ">*</i></label>
                                        <input type="text" class="form-control number" placeholder="Enter Mobile Number"
                                            name="phone" required="" minlength="10" maxlength="10"
                                            value="<?php echo e(old('phone')); ?>">
                                        <div class="text-danger"><?php echo e($errors->first('phone')); ?></div>
                                    </div>
                                    <br>
                                    <div class="col-sm-6" style="margin-top: 9px;">
                                        <input type="submit" name="submit" class="btn btn-sm btn-primary" value="Search">
                                        <input type="reset" name="reset" value="Cancel" class="btn btn-sm btn-default">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="card-box">
                            <form method="post" action="<?php echo e(url('admin/findpage')); ?>" class="form-validate-summernote"
                                enctype="multipart/form-data">
                                <?php echo csrf_field(); ?>
                                <div class="row form-group">
                                    <div class="col-xs-12">
                                        <div class="col-sm-2">
                                            <label class="">Page Number<i class="text-danger ">*</i></label>
                                            <input type="number" class="form-control number" name="page" required=""
                                                max="10000000" min="1" value="<?php echo e(old('page')); ?>" maxlength="7"
                                                placeholder="1">
                                            <div class="text-danger"><?php echo e($errors->first('page')); ?></div>
                                        </div>
                                        <div class="col-sm-2">
                                            <label class="">Number of Records<i class="text-danger ">*</i></label>
                                            <input type="number" class="form-control" name="records" required=""
                                                max="100" min="1" maxlength="3" value="<?php echo e(old('records')); ?>"
                                                placeholder="10">
                                            <div class="text-danger"><?php echo e($errors->first('records')); ?></div>
                                        </div>
                                        <br>
                                        <div class="col-sm-2" style="margin-top: 10px;">
                                            <input type="submit" name="submit" class="btn btn-sm btn-primary"
                                                value="Submit">
                                            <input type="reset" name="reset" value="Cancel"
                                                class="btn btn-sm btn-default">
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div class="row form-group">
                                <div class="col-xs-12">
                                    <div class="col-sm-2">
                                        <label class="">Fillter<i class="text-danger ">*</i></label>
                                    </div>
                                    <div class="col-sm-2">
                                        <select name="option" id="option" class="form-control" onchange="fillter()" required>
                                            <option value="">Select Option</option>
                                            <?php $__currentLoopData = $usersWithCompany; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($val->user_id); ?>" <?php echo e((isset($_GET['option']) && $_GET['option'] == $val->user_id)?'selected':''); ?>><?php echo e($val->company_name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
                                width="100%" id="">
                                    <thead>
                                        <tr>
                                            <th>S.NO.</th>
                                            <th>Phone Number</th>
                                            <th>Federal</th>
                                            <th>Litigator</th>
                                            <th>Internal</th>
                                            <th>Wireless</th>
                                            <th>Uploaded By</th>
                                            <th>Modified By</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $dnc; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key =>  $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php
                                                $user = App\Models\User::where('id',$data->uploaded_by)->first('name');
                                                    
                                            ?>
                                            <tr>
                                                <td><?php echo e($dnc->firstItem() + $key); ?></td>
                                                <td><?php echo e($data->phone_no); ?></td>
                                                <td><?php echo e($data->federal); ?></td>
                                                <td><?php echo e($data->litigator); ?></td>
                                                <td><?php echo e($data->internal); ?></td>
                                                <td><?php echo e($data->wireless); ?></td>
                                                <td><?php echo e($user->name); ?></td>
                                                <td><?php echo e($data->updated_at->setTimezone('America/New_York')); ?></td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-center">
                                    <?php echo e($dnc->links()); ?>

                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div> <!-- container -->
        </div> <!-- content -->
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('footerscript'); ?>
    
    <script src="<?php echo e(asset('theme/plugins/bootstrap-sweetalert/sweet-alert.min.js')); ?>"></script>
    <script>
        function fillter() {
            var selectedOption = $('#option').val();
            window.location = "<?php echo e(url('admin/dnclist')); ?>?option="+selectedOption;
        }    
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/dncscrubbing/resources/views/admin/dnclist.blade.php ENDPATH**/ ?>
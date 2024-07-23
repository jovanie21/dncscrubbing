<?php $__env->startSection('title', 'Clients'); ?>
<?php $__env->startPush('headerscript'); ?>
    <link href="<?php echo e(asset('theme/plugins/bootstrap-sweetalert/sweet-alert.css')); ?>" rel="stylesheet" type="text/css">
    <link href="<?php echo e(asset('theme/plugins/datatables/responsive.bootstrap.min.css')); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo e(asset('theme/plugins/datatables/dataTables.bootstrap.min.css')); ?>" rel="stylesheet" type="text/css" />
    <style type="text/css">
        div.dataTables_wrapper div.dataTables_processing {
            top: 0;
            color: red;
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
                            <h4 class="page-title">Company </h4>
                            <a href="<?php echo e(route('company.create')); ?>" class="btn btn-primary btn-sm pull-right"><i
                                    class="fa fa-plus"></i> Add New Company</a>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <!-- end row -->
                <div class="row">

                    <div class="col-xs-12">
                        <div class="card-box">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
                                    width="100%" id="datatables">
                                    <thead>
                                        <tr>
                                            <th>S.NO.</th>
                                            <th>Company Name</th>
                                            <th>Email ID</th>
                                            <th>Phone Number</th>
                                            <th>Company Zip</th>
                                            <th>Created At</th>
                                            <th>Updated_at</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <?php $__currentLoopData = $usersWithCompanyDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($key + 1); ?></td>
                                            <td><?php echo e($user->company_name); ?></td>
                                            <td><?php echo e($user->email); ?></td>
                                            <td><?php echo e($user->phone_number); ?></td>
                                            <td><?php echo e($user->company_zip); ?></td>
                                            <td><?php echo e($user->created_at->setTimezone('America/New_York')); ?></td>
                                            <td><?php echo e($user->updated_at->setTimezone('America/New_York')); ?></td>
                                            <td><a href="<?php echo e(route('company.edit', $user->user_id)); ?>"
                                                    class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i>
                                                    Edit</a>&nbsp&nbsp
                                                    <a href="<?php echo e(route('changepass', $user->user_id)); ?>"
                                                        class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-wrench"></i>
                                                        Password</a>&nbsp&nbsp
                                                <button class="btn btn-xs btn-danger"
                                                    onclick="deleteit(<?php echo e($user->user_id); ?>)"><i
                                                        class="glyphicon glyphicon-trash"></i> Delete</button>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </table>
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
    <script src="<?php echo e(asset('theme/plugins/datatables/jquery.dataTables.min.js')); ?>"></script>
    <script src="<?php echo e(asset('theme/plugins/datatables/dataTables.responsive.min.js')); ?>"></script>
    <script src="<?php echo e(asset('theme/plugins/datatables/dataTables.bootstrap.js')); ?>"></script>
    <script>
        $(document).ready(function() {
            $('#datatables').DataTable();
        });

        function deleteit(userId) {
            swal({
                    title: "Are you sure?",
                    text: "You will not be able to recover this imaginary file!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: '#DD6B55',
                    confirmButtonText: 'Yes, I am sure!',
                    cancelButtonText: "No, cancel it!",
                    closeOnConfirm: false,
                    closeOnCancel: false,
                    cancelButtonClass: 'btn-default btn-md waves-effect',
                    confirmButtonClass: 'btn-danger btn-md waves-effect waves-light',
                },
                function(isConfirm) {
                    if (isConfirm) {
                        $.ajax({
                            url: '<?php echo e(url('admin/company')); ?>/' + userId,
                            type: 'delete',
                            dataType: "JSON",
                            data: {
                                "id": userId,
                                "_token": "<?php echo e(csrf_token()); ?>"
                            },
                            success: function(response) {
                                swal("Deleted!", "Company has been deleted!", "success");
                                location.reload();
                            },
                            error: function(xhr) {
                                swal("Error", "Could not delete company", "error");
                            }
                        });
                    } else {
                        swal("Cancelled", "User data is safe :)", "error");
                    }
                });
        }
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\DNCscrubbing\resources\views/admin/company/index.blade.php ENDPATH**/ ?>
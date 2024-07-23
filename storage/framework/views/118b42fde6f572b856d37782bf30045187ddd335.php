<?php $__env->startSection('title', 'Scrub Upload Files'); ?>
<?php $__env->startPush('headerscript'); ?>
<link href="<?php echo e(asset('theme/plugins/bootstrap-sweetalert/sweet-alert.css')); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo e(asset('theme/plugins/datatables/responsive.bootstrap.min.css')); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo e(asset('theme/plugins/datatables/dataTables.bootstrap.min.css')); ?>" rel="stylesheet" type="text/css" />
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
                        <h4 class="page-title">Scrub Upload File</h4>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <!-- end row -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="card-box" id="uploadhide">
                        <form method="post" action="<?php echo e(url('admin/storescrubfile')); ?>" class="form-validate-summernote" id="myform" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <div class="row form-group">
                                <div class="col-sm-2">
                                    <label>Scrub Type</label>
                                </div>
                                <div class="col-sm-6">
                                    <div class="row">
                                        
                                        <span class="col-sm-2">
                                            <input type="checkbox" name="type1[internal]" id="internal" value="yes">
                                            <label for="internal">Internal</label>
                                        </span>
                                        <span class="col-sm-2">
                                            <input type="checkbox" name="type1[federal]" id="federal" value="yes">
                                            <label for="federal">Federal</label>
                                        </span>
                                        <span class="col-sm-2">
                                            <input type="checkbox" name="type1[litigator]" id="litigator" value="yes">
                                            <label for="litigator">Litigator</label>
                                        </span>
                                        <span class="col-sm-2">
                                            <input type="checkbox" name="type1[wireless]" id="wireless" value="yes">
                                            <label for="wireless">Wireless</label>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-sm-2">
                                    <label class="">Upload Name<i class="text-danger ">*</i></label>
                                </div>
                                <div class="col-sm-6">
                                    <input type="text" name="name" value="<?php echo e(old('name')); ?>" class="form-control" required="" placeholder="Upload Name">
                                    <div class="text-danger"><?php echo e($errors->first('name')); ?></div>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-sm-2">
                                    <label class="">Upload(Browse File)<i class="text-danger ">*</i></label>
                                </div>
                                <div class="col-sm-6">
                                    <input type="file" name="file" class="form-control" required="">
                                    <div class="text-danger"><?php echo e($errors->first('file')); ?></div>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-sm-2">
                                    <label class="">DNC List</label>
                                </div>
                                <div class="col-sm-6">
                                    <select name="region[]" id="region" class="form-control select2 w-100" multiple="multiple">
                                        <!-- <option value="">Select Regions</option> -->
                                        <?php $__currentLoopData = $regions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $region): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($region->id); ?>"><?php echo e($region->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <div class="text-danger"><?php echo e($errors->first('file')); ?></div>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-sm-2">
                                    <label for="">Scrubing Option<i class="text-danger ">*</i></label>
                                </div>
                                <div class="col-sm-6">
                                    <select name="option" id="option" class="form-control" required>
                                        <option value="">Select Option</option>
                                        <option value="combined">Combined</option>
                                        <option value="seperate">Seperate (DNC or NON-DNC)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-sm-12">
                                    <div class="col-sm-10">
                                        <input type="submit" id="submit" name="submit" class="btn btn-sm btn-primary" value="Submit">
                                        <input type="reset" id="reset" name="reset" value="Cancel" class="btn btn-sm btn-default">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="row" id="loader" style="display: none">
                        <div class="col-sm-12" style="margin-left: 40%;">
                            <div class="loader"></div>
                            <h3>Please Wait....</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="page-title-box">
                        <h4 class="page-title text-primary">Uploaded File</h4>
                        <div class="clearfix"></div>
                    </div>
                    <div class="col-xs-12">
                        <div class="card-box">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%" id="datatables">
                                    <thead>
                                        <tr>
                                            <th>S.NO.</th>
                                            <th>Name</th>
                                            <th>Processed</th>
                                            <th>Uploaded Date</th>
                                            <th>Finished time</th>
                                            <th>Action</th>
                                            <th>Receipt</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $exports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $export): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($loop->iteration); ?></td>
                                            <td><?php echo e($export->name); ?></td>
                                            <td class="text-center">
                                                <label class="label label-<?php echo e($export->status == 'processed' ? 'success' : ($export->status == 'processing' ? 'info' : 'danger')); ?>"><?php echo e(ucwords($export->status)); ?></label>
                                            </td>
                                            <td>
                                                <?php echo e(date('m/d/Y H:i:s', strtotime($export->created_at->setTimezone('America/New_York')))); ?>

                                            </td>
                                            <td>
                                                <?php if($export->status === 'processed'): ?>
                                                <?php echo e(date('m/d/Y h:i:s', strtotime($export->updated_at->setTimezone('America/New_York')))); ?>

                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <?php if(!count(array_filter(array_keys($export->paths), 'is_string')) > 0): ?>

                                                <?php
                                                $paths = [];
                                                foreach ($export->paths as $key => $path) {
                                                $paths [$key] = $path;

                                                if(!isset($path['invalid']) || !Storage::disk('dnc-seperated')->exists($path['invalid'])) {
                                                unset($paths[$key]['invalid']);
                                                }

                                                }
                                                $export->paths = $paths;
                                                ?>


                                                <?php else: ?>

                                                <?php if($export->scrubing_option !== 'seperate'): ?>

                                                <span style="display:flex; justify-content: space-evenly;">
                                                    <a class="btn btn-success" href="<?php echo e($export->status == 'processed' ? asset('dnc-seperated/' . $export->paths['combined']) : ''); ?>">DNC/NonDnc</a>

                                                </span>

                                                <?php else: ?>
                                                <span style="display:flex; justify-content: space-evenly;">
                                                    <a class="btn btn-success" href="<?php echo e($export->status == 'processed' ? asset('dnc-seperated/' . $export->paths['dnc']) : ''); ?>">DNC</a>
                                                    <a class="btn btn-danger" href="<?php echo e($export->status == 'processed' ? asset('dnc-seperated/' . $export->paths['non_dnc']) : ''); ?>">NON-DNC</a>


                                                    <?php if(!empty($export->invalid_dnc_count)): ?>
                                                    <a class="btn btn-warning" href="<?php echo e($export->status == 'processed' ? asset('dnc-seperated/' . $export->paths['invalid']) : ''); ?>">INVALID</a>
                                                    <?php endif; ?>
                                                </span>

                                                <?php endif; ?>

                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <a href="<?php echo e(url('admin/pdf/' . $export->id . '')); ?>" class="btn btn-sm btn-success" target="_blank">
                                                    Genrate Pdf
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- container -->
    </div> <!-- content -->
    <div class="modal fade" id="pathsModal" tabindex="-1" aria-labelledby="pathsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pathsModalLabel">Download Files</h5>
                </div>
                <div class="modal-body">
                </div>
            </div>
        </div>
    </div>
</div>
<button type="button" class="d-none" id="modalBtn" data-toggle="modal" data-target="#pathsModal"></button>
<div class="" id="regions" data-content="<?php echo e(json_encode($regions)); ?>"></div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('footerscript'); ?>
<script src="<?php echo e(asset('theme/plugins/bootstrap-sweetalert/sweet-alert.min.js')); ?>"></script>
<script src="<?php echo e(asset('theme/plugins/datatables/jquery.dataTables.min.js')); ?>"></script>
<script src="<?php echo e(asset('theme/plugins/datatables/dataTables.responsive.min.js')); ?>"></script>
<script src="<?php echo e(asset('theme/plugins/datatables/dataTables.bootstrap.js')); ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript">
    $('#datatables').dataTable();
    $('select.select2').select2();
    var regions = $('#regions').data('content');
</script>
<script>
    function deleteit(id) {
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

                    $('#datatables').dataTable().draw(false);
                    swal("Deleted!", "User has been deleted!", "success");
                    //window.location.reload();
                } else {
                    swal("Cancelled", "User data is safe :)", "error");
                }

            });

    }
</script>
<!-- <script>
        setInterval(function() {
            location.reload();
        }, 60000);
    </script> -->

<!-- <script>
        setInterval(function() {
            location.reload();
        }, 120000);

        <?php if(session('success_msg')): ?>
            alert('<?php echo e(session('success_msg')); ?>');
        <?php endif; ?>
    </script> -->

<script>
    $(document).on('click', '.modalBtn', function() {
        var content = $(this).data('content');
        var html = '';
        console.log(content, regions);
        for (i in content) {
            html +=
                `<div class="row"><div class="col-sm-6"><h5>${regions[(i-1)]?.name}</h5></div><div class="col-sm-6" style="display:flex; justify-content: space-evenly;"><a class="btn btn-success" href="<?php echo e(asset('dnc-seperated/${content[i]?.active}')); ?>">DNC</a><a class="btn btn-danger" href="<?php echo e(asset('dnc-seperated/${content[i]?.inactive}')); ?>">NON-DNC</a></div></div>`;

            if (content[i].invalid !== undefined) {
                html += `<a class="btn btn-warning" href="<?php echo e(asset('dnc-seperated/${content[i]?.invalid}')); ?>">INVALID-DNC</a></div></div>`;
            }
        }
        html += '</div>';
        $('#pathsModal').find('.modal-body').html(html);
        $('#modalBtn').click();
    });
</script>
<script>
    $(document).ready(function() {

        $('input[type="checkbox"]').click(function() {

            var checkedValues = [];
            $('input[type="checkbox"]').each(function() {

                if ($(this).is(':checked')) {
                    checkedValues.push({
                        name: $(this).attr('id'),

                    });
                }
            });
            if (checkedValues.length > 0) {

                $.ajax({
                    type: 'POST',
                    url: "<?php echo e(route('dnc.fillter')); ?>",
                    data: {
                        "_token": '<?php echo e(csrf_token()); ?>',
                        "checkedValues": checkedValues
                    },
                    success: function(response) {
                        console.log(response);
                        var select = '<select name="region[]" id="region" class="form-control select2 w-100" multiple="multiple">';
                        $.each(response, function(index, value) {
                            select += '<option value="' + value.id + '">' + value.region.name + '</option>';
                        });
                        select += '</select>';
                        $('#region').html(select);
                    },
                    error: function(error) {
                        // console.error(error);
                    }
                });
            }

        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('admin.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/dncscrubbing/resources/views/admin/scrub/scrubupload.blade.php ENDPATH**/ ?>
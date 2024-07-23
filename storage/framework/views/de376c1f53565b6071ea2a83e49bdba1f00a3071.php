
<link href="<?php echo e(asset('theme/plugins/toastr/toastr.min.css')); ?>" rel="stylesheet" type="text/css" />
     <script src="<?php echo e(asset('theme/plugins/toastr/toastr.min.js')); ?>"></script>
        <!-- Toastr init js (Demo)-->
        <script src="<?php echo e(asset('theme/default/assets/pages/jquery.toastr.js')); ?>"></script>
<?php if(session()->has('success_msg')): ?>
    <script>
        Command: toastr["success"]("<?php echo e(session()->get('success_msg')); ?>", "successfully")

toastr.options = {
  "closeButton": false,
  "debug": false,
  "newestOnTop": false,
  "progressBar": false,
  "positionClass": "toast-top-right",
  "preventDuplicates": false,
  "onclick": null,
  "showDuration": "600",
  "hideDuration": "1000",
  "timeOut": "5000",
  "extendedTimeOut": "1000",
  "showEasing": "swing",
  "hideEasing": "linear",
  "showMethod": "fadeIn",
  "hideMethod": "fadeOut"
}
</script>
<?php endif; ?>
<?php if(session()->has('danger_msg')): ?>
 <script>
        Command: toastr["error"]("<?php echo e(session()->get('danger_msg')); ?>", "Unsuccessfully")

toastr.options = {
  "closeButton": false,
  "debug": false,
  "newestOnTop": false,
  "progressBar": false,
  "positionClass": "toast-top-right",
  "preventDuplicates": false,
  "onclick": null,
  "showDuration": "300",
  "hideDuration": "1000",
  "timeOut": "5000",
  "extendedTimeOut": "1000",
  "showEasing": "swing",
  "hideEasing": "linear",
  "showMethod": "fadeIn",
  "hideMethod": "fadeOut"
}
</script>
<?php endif; ?>
<?php if(session()->has('warning_msg')): ?>
 <script>
        Command: toastr["success"]("<?php echo e(session()->get('success_msg')); ?>", "Your Token has been Successfully Deleted")

toastr.options = {
  "closeButton": false,
  "debug": false,
  "newestOnTop": false,
  "progressBar": false,
  "positionClass": "toast-top-right",
  "preventDuplicates": false,
  "onclick": null,
  "showDuration": "300",
  "hideDuration": "1000",
  "timeOut": "5000",
  "extendedTimeOut": "1000",
  "showEasing": "swing",
  "hideEasing": "linear",
  "showMethod": "fadeIn",
  "hideMethod": "fadeOut"
}
</script>
<?php endif; ?><?php /**PATH /var/www/dncscrubbing/resources/views/admin/layout/notificaton.blade.php ENDPATH**/ ?>
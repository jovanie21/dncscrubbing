
<link href="{{ asset('theme/plugins/toastr/toastr.min.css') }}" rel="stylesheet" type="text/css" />
     <script src="{{ asset('theme/plugins/toastr/toastr.min.js') }}"></script>
        <!-- Toastr init js (Demo)-->
        <script src="{{ asset('theme/default/assets/pages/jquery.toastr.js') }}"></script>
@if(session()->has('success_msg'))
    <script>
        Command: toastr["success"]("{{session()->get('success_msg')}}", "successfully")

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
@endif
@if(session()->has('danger_msg'))
 <script>
        Command: toastr["error"]("{{session()->get('danger_msg')}}", "Unsuccessfully")

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
@endif
@if(session()->has('warning_msg'))
 <script>
        Command: toastr["success"]("{{session()->get('success_msg')}}", "Your Token has been Successfully Deleted")

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
@endif
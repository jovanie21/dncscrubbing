@extends('admin.layout.app')
@section('title','Clients')
@push('headerscript')
<link href="{{ asset('theme/plugins/bootstrap-sweetalert/sweet-alert.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('theme/plugins/datatables/responsive.bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('theme/plugins/datatables/dataTables.bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
<style type="text/css">
    div.dataTables_wrapper div.dataTables_processing {
  top: 0;
    color: red;
}
</style>
@endpush
@section('content')
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
                        <h4 class="page-title">Clients </h4>
                            <a href="{{ url('admin/user/create') }}" class="btn btn-primary btn-sm pull-right"><i class="fa fa-plus"></i> Add New</a>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <!-- end row -->
            <div class="row">
                
                <div class="col-xs-12">
                    <div class="card-box">
                        <div class="table-responsive">
                        <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%" id="datatables">
                            <thead>
                                <tr>
                                    <th>S.NO.</th>
                                    <th>User Name</th>     
                                    <th>Email ID</th>              
                                    <th>Actual Password</th>              
                                    <th>Phone Number</th>              
                                    <th>Company Name</th>              
                                    <th>Dialplan Entry</th>              
                                    <th>Company Zip</th>              
                                    <th>Created At</th>              
                                    <th>Updated_at</th>              
                                    <th>Action</th>              
                                </tr>
                            </thead>
                            
                        </table>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- container -->
    </div> <!-- content -->
</div>
@endsection
@push('footerscript')
<script src="{{ asset('theme/plugins/bootstrap-sweetalert/sweet-alert.min.js')}}"></script>
<script src="{{ asset('theme/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{ asset('theme/plugins/datatables/dataTables.responsive.min.js')}}"></script>
<script src="{{ asset('theme/plugins/datatables/dataTables.bootstrap.js')}}"></script>
<script>
    function deleteit(id){
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
        function(isConfirm){
            if (isConfirm){
                $.ajax({
                    url: '{{ url('admin/user') }}/'+id,
                    type: 'delete',
                    dataType: "JSON",
                    data: {
                        "id": id,
                        "_token":"{{ csrf_token() }}"
                    },
                });
                $('#datatables').DataTable().draw(false);
                swal("Deleted!", "User has been deleted!", "success");
            //window.location.reload();
        } else {
            swal("Cancelled", "User data is safe :)", "error");
        }

    });

    }
</script>
<script type="text/javascript">
    $(function() {
        $('#datatables').DataTable({
            processing: true,
            serverSide: true,
            stateSave: true,
            ajax: '{{ url('admin/user/getData') }}',
            columns: [
            { data: 'id', name: 'users.id', searchable: false},               
            { data: 'name', name: 'name' },          
            { data: 'email', name: 'email' },       
            { data: 'actual_password', name: 'actual_password' },       
            { data: 'phone_number', name: 'user_details.phone_number' },       
            { data: 'company_name', name: 'user_details.company_name' },       
            { data: 'company_address', name: 'user_details.company_address' },       
            { data: 'company_zip', name: 'user_details.company_zip' },       
            { data: 'created_at', name: 'user_details.created_at' },       
            { data: 'updated_at', name: 'user_details.updated_at' },       
            {data: 'action', name: 'action', orderable: false, searchable: false}           
            ]
        });
    });
</script>

@endpush
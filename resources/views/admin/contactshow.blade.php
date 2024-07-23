@extends('admin.layout.app')
@section('title','Contact')
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
                        <h4 class="page-title">Contact </h4>
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
                                    <th>First Name</th>     
                                    <th>Last Name</th>     
                                    <th>Email ID</th>              
                                    <th>Phone Number</th>              
                                    <th>Message</th>              
                                    <th>Created At</th>              
                                    <th>Updated At</th>              
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
<script type="text/javascript">
    $(function() {
        $('#datatables').DataTable({
            processing: true,
            serverSide: true,
            stateSave: true,
            ajax: '{{ url('admin/getcontactdata') }}',
            columns: [
            { data: 'id', name: 'id', searchable: false},               
            { data: 'first_name', name: 'first_name' },          
            { data: 'last_name', name: 'last_name' },          
            { data: 'email', name: 'email' },       
            { data: 'phone_no', name: 'phone_no' },       
            { data: 'message', name: 'message' },       
            { data: 'created_at', name: 'created_at' },       
            { data: 'updated_at', name: 'updated_at' },      
            {data: 'action', name: 'action', orderable: false, searchable: false}           

             
            ]
        });
    });
</script>

@endpush
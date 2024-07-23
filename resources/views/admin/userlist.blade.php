@extends('admin.layout.app')
@section('title','Client DNC List')
@push('headerscript')
<link href="{{ asset('theme/plugins/datatables/responsive.bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('theme/plugins/datatables/dataTables.bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>

<link href="{{ asset('theme/plugins/summernote/summernote.css') }}" rel="stylesheet" />
<style>
  .summernote{
    position: absolute;
    flex: initial;
  }
</style>
<style>
  .loader {
    border: 16px solid #ccc;
    border-radius: 100%;
    border-top: 16px solid #3498db;
    width: 120px;
    height: 120px;
    -webkit-animation: spin 2s linear infinite; /* Safari */
    animation: spin 2s linear infinite;
  }

  /* Safari */
  @-webkit-keyframes spin {
    0% { -webkit-transform: rotate(0deg); }
    100% { -webkit-transform: rotate(360deg); }
  }

  @keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
  }
  div.dataTables_wrapper div.dataTables_processing {
  top: 0;
    color: red;
}
</style>
<link href="{{ asset('theme/plugins/bootstrap-sweetalert/sweet-alert.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('theme/plugins/datatables/responsive.bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('theme/plugins/datatables/dataTables.bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>

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
                   <div class="page-title-box">
            <h4 class="page-title text-primary" >Client DNC List</h4>
            <div class="clearfix"></div>
          </div>
                <div class="col-xs-12">
                    <div class="card-box">
                       <div class="row form-group">
                       <div class="col-sm-3">
                   <label for="">Filter By Client Name: </label>

                           <select class="form-control" id="filter_date">
                    <option value="">--Select--</option>
                    @foreach($total_user as $r)
                    <option value= "{{ $r->id }}" >{{ $r->name }}</option>
                    @endforeach
                </select>
                       </div>
                   </div>
                        <div class="table-responsive">
                        <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%" id="datatables">
                            <thead>
                                <tr>
                                    <th>S.NO.</th>
                                    <th>Phone No</th>     
                                    <th>Created By</th>              
                                    <th>Created At</th>              
                                    <th>Updated At</th>              
                                </tr>
                            </thead>
                            
                        </table>
                        </div>
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
            ajax: {
                url: '{{ url('admin/GetUserList') }}',
                data: function (d) {
                    d.filter_date = $('#filter_date').val();
                }
            },
            columns: [
            { data: 'rownum', name: 'rownum', searchable: false},               
            { data: 'phone_no', name: 'user_dnc_lists.phone_no' },          
            { data: 'name', name: 'users.name' },       
            { data: 'created_at', name: 'user_dnc_lists.created_at' },       
            { data: 'updated_at', name: 'user_dnc_lists.updated_at' },       
            ]
            
        });
    });
</script>
<script type="text/javascript">
    $('#filter_date').on('change', function () {
        $('#datatables').DataTable().draw(false)
    });
</script>



@endpush
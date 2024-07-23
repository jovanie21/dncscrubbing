@extends('admin.layout.app')
@section('title','Client Tokens')
@push('headerscript')

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
            <h4 class="page-title text-primary" >Token Details of <span class="text-info">{{ $users->name }}</span></h4>
             <a onclick="generatetoken({{$users->id}} )" class="btn btn-info btn-sm pull-right"><i class="fa fa-plus"></i> Generate Token</a>
            <div class="clearfix"></div>
          </div>
                <div class="col-xs-12">
                    <div class="card-box">
                        <div class="table-responsive">
                        <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%" id="datatables">
                            <thead>
                                <tr>
                                    <th>S.NO.</th>
                                    <th>Token</th>     
                                    <th>Token Status</th>
                                    <th>Created At</th>
                                    <th>Updated At</th>
                                    <th>Action</th>              
                                </tr>
                            </thead>
                            <tbody>
                              @foreach($toekndetails as $r)
                                <tr>
                                  <td>{{ $loop->iteration }}</td>
                                  <td>{{ $r->token }}</td>
                                                                    <td>
                                    {{ date("d-M-Y h:i:s A",strtotime($r->created_at)) }}
                                  </td>
                                  <td>
                                    {{ date("d-M-Y h:i:s A",strtotime($r->updated_at)) }}
                                  </td>
                                  <td>
                                    @if($r->status=='1')
                                     <button class="btn btn-success btn-sm disabled"><i class="fa fa-check-circle-o" aria-hidden="true"></i> &nbsp;&nbsp;&nbsp;Active</button>
                                    @else
                                     <button class="btn btn-danger btn-sm disabled"><i class="fa fa-times" aria-hidden="true"></i> In Active</button>
                                    @endif
                                  </td>
                                  <td>
                                    @if($r->status=='1')

                                    <a onclick="deleteit({{$r->id}} )" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i>&nbsp;&nbsp; Delete</a>

                                    @else
                                    <a onclick="deleteit({{$r->id}} )" class="btn btn-danger btn-sm disabled"><i class="fa fa-trash"></i> Deleted</a>

                                    @endif
                                  </td>
                                </tr>
                              @endforeach
                            </tbody>
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
<script>
    function generatetoken(id){
        swal({
            title: "Are you sure?",
            text: "You Want to Create Generate Token!",
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
                    url: '{{ url('admin/user/generateToken') }}/'+id,
                    type: 'get',
                    dataType: "JSON",
                    data: {
                        "id": id,
                        "_token":"{{ csrf_token() }}"
                    },
                });
                $('#datatables').DataTable().draw(false);
                swal("Generated!", "Token Generated Successfully!", "success");
            window.location.reload();
        } else {
            swal("OOps!!!", "You have clicked on cancel :)", "error");
        }

    });

    }
</script>


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
                    url: '{{ url('admin/user/deactivatetoken') }}/'+id,
                    type: 'get',
                    dataType: "JSON",
                    data: {
                        "id": id,
                        "_token":"{{ csrf_token() }}"
                    },
                });
                $('#datatables').DataTable().draw(false);
                swal("Deleted!", "User has been deleted!", "success");
            window.location.reload();
        } else {
            swal("Cancelled", "User data is safe :)", "error");
        }

    });

    }
</script>


<script type="text/javascript">
  $('#datatables').DataTable();
</script>
@endpush
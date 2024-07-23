@extends('user.layout.app')
@section('title','Upload Files')
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
        <h4 class="page-title text-primary" >Token Details</h4>
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
                </thead>
                <tbody>
                  @foreach($details as $r)
                  <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $r->token }}</td>
                    <td>
                      @if($r->status=='1')
                      <label class="label label-success">Active</label>
                      @else
                      <label class="label label-danger">Expired</label>
                      @endif
                    </td>
                    <td>
                      {{ date("d-M-Y h:i:s A",strtotime($r->created_at)) }}
                    </td>
                    <td>
                      {{ date("d-M-Y h:i:s A",strtotime($r->updated_at)) }}
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
<script src="{{ asset('theme/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{ asset('theme/plugins/datatables/dataTables.responsive.min.js')}}"></script>
<script src="{{ asset('theme/plugins/datatables/dataTables.bootstrap.js')}}"></script>
<script type="text/javascript">
  $('#datatables').DataTable();
</script>
@endpush
@extends('admin.layout.app')
@section('title','Client Scrubbing List')
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
<link href="{{ asset('theme/plugins/bootstrap-sweetalert/sweet-alert.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('theme/plugins/datatables/responsive.bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('theme/plugins/datatables/dataTables.bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>

@endpush
@section('content')
<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->
<div class="content-page">
  <div class="content">
    <div class="container">
      <div class="row">
        <div class="page-title-box">
          <h4 class="page-title text-primary" >Client Scrubbing List</h4>
          <div class="clearfix"></div>
        </div>
        <div class="col-xs-12">
          <div class="card-box">                       
            <div class="table-responsive">
              <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%" id="datatables">
                  <thead>
                      <tr>
                          <th>S.NO.</th>
                          <th>Client Email</th>     
                          <th>File Name</th>     
                          <th>Processed</th>              
                          <th>File Progress</th>              
                          <th>Created At</th>              
                          <th>Updated At</th>              
                          <th>Action</th>              
                          <th>Receipt</th>              
                      </tr>
                  </thead>
                  <tbody>
                      @foreach($total_user as $r)
                      <tr>
                        <td>{{ $loop->iteration }}</td>                        
                        <td>{{ $r->email }}</td>
                        <td>{{ $r->upload_name }}</td>
                        <td>
                          @if($r->is_processed=='1')
                          <label class="label label-primary">UnProcessed</label>
                          @else
                          <label class="label label-success">Processed</label>
                          @endif
                        </td>
                        <td>                                        
                          @if($r->total_rows!=0 && $r->is_dump==1)
                            @if($r->is_processed=='1')
                              @if($r->remaining_rows=='0')
                              <progress id="file" max="100" value="0"></progress>
                              <p>Progress:0%</p>
                              @else
                              <progress id="file" max="100" value="{{$percent}}"></progress>
                              <p>Progress:{{$percent}}%</p>
                              @endif
                            @else
                              <progress id="file" max="100" value="100"> 100% </progress>
                              <p>Progress: Completed</p>
                            @endif
                          @else
                            <progress id="file" max="100" value="0"></progress>
                            <p>Progress:Starting...</p>
                          @endif
                        </td>
                        <td>
                          {{ date("d-M-Y h:i:s A",strtotime($r->created_at)) }}
                        </td>
                        <td>
                          {{ date("d-M-Y h:i:s A",strtotime($r->updated_at)) }}
                        </td>
                        <td>
                          <?php
                          $data=$r->identical_file_path;
                          ?>
                          @if(is_null($data))
                          <span class="label label-success">Process File</span>
                          @else
                          <a href="https://s3.us-east-2.amazonaws.com/files.dncblocker.com/{{$r->identical_file_path}}" class="btn btn-sm btn-success" download="">DNC</a>
                          <a href="https://s3.us-east-2.amazonaws.com/files.dncblocker.com/{{$r->unidentical_file_path}}" class="btn btn-sm btn-danger" download="">NON DNC</a>
                          @endif
                        </td>
                        <td>
                          <a href="{{ url('users/pdf/'.$r->id.'') }}" class="btn btn-sm btn-success" target="_blank"> Genrate Pdf</a>
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

@endsection
@push('footerscript')

<script src="{{ asset('theme/plugins/bootstrap-sweetalert/sweet-alert.min.js')}}"></script>
<script src="{{ asset('theme/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{ asset('theme/plugins/datatables/dataTables.responsive.min.js')}}"></script>
<script src="{{ asset('theme/plugins/datatables/dataTables.bootstrap.js')}}"></script>


<script type="text/javascript">
  $(function() {
    $('#datatables').DataTable();
  });
</script>



@endpush
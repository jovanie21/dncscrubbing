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
      div.dataTables_wrapper div.dataTables_processing {
  top: 0;
    color: red;
}
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
        <div class="col-xs-12">
          <div class="page-title-box">
            <h4 class="page-title">Upload File</h4>
            <div class="clearfix"></div>
          </div>
        </div>
      </div>
      <!-- end row -->
      <div class="row">
        <div class="col-sm-12">
          <div class="card-box" id="uploadhide">
            <form method="post" action="{{ url('user/uploaduserfile') }}" class="form-validate-summernote" id="myform"  enctype="multipart/form-data">
              @csrf
              <div class="row form-group">
                <div class="col-sm-2">
                  <label class="">Upload Name<i class="text-danger ">*</i></label>
                </div>
                <div class="col-sm-6">
                  <input type="text" name="name" value="{{ old('name') }}" class="form-control" required="" placeholder="Upload Name">
                  <div class="text-danger">{{ $errors->first('name') }}</div>
                </div>
              </div>
              <div class="row form-group">
                <div class="col-sm-2">
                  <label class="">UPLOAD (Browse File)<i class="text-danger ">*</i></label>
                </div>
                <div class="col-sm-6">
                  <input type="file" name="file" class="form-control" required="">
                  <div class="text-danger">{{ $errors->first('file') }}</div>
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
            <h4 class="page-title text-primary" >Uploaded File</h4>
            <div class="clearfix"></div>
          </div>
                <div class="col-xs-12">
                    <div class="card-box">
                        <div class="table-responsive">
                            
                          <a id="buttonnew" class="btn btn-primary pull-right" onclick="check()" style="margin-bottom: 1%;">Bulk Process</a>
                          <input type="checkbox" class="pull-left" id="checkAll" name="checkAll"><span class="pull-left"><strong> &nbsp; Check All</strong></span>
                        <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%" id="datatables">
                            <thead>
                                <tr>
                                    <th>S.NO.</th>
                                    <th>#</th>
                                    <th>Name</th>     
                                    <th>file</th>              
                                    <th>Processed</th>              
                                    <th>Created At</th>              
                                    <th>Updated At</th>              
                                    <th>Action</th>              
                                </tr>
                            </thead>
                            <tbody>
                              
                               
                              @foreach($uploaded as $r)
                                <tr>
                                  <td>{{ $loop->iteration }}</td>
                                  <td style="display: none">{{ $r->id }}</td>

                                  <td>
                                     @if($r->is_processed=='1')
                                      <input type="checkbox" id="checkboxselect" value="{{$r->id}}" name="check"> 
                                      @endif                                                  
                                  </td>
                                  <td>{{ $r->upload_name }}</td>
                                  <td>
                                    @if($r->is_deleted=='1')
                                    <a href="{{ asset($r->file_path) }}" download> <label>Download File</label></a>
                                    @else
                                    <label class="label label-danger">File deleted</label>
                                    @endif
                                  </td>
                                  <td>@if($r->is_processed=='1')
                                    <label class="label label-primary">UnProcessed</label>
                                    @else
                                    <label class="label label-success">Processed</label>
                                  @endif</td>
                                                                    <td>
                                    {{ date("d-M-Y h:i:s A",strtotime($r->created_at)) }}
                                  </td>
                                  <td>
                                    {{ date("d-M-Y h:i:s A",strtotime($r->updated_at)) }}
                                  </td>
                                  <td>
                                    @if($r->is_deleted=='1')
                                      @if($r->is_processed=='1')
                                      <a  href="{{ url("user/processfile/$r->id") }}" class="btn btn-primary btn-sm" id="process">Process File</a>
                                      @else
                                       <button class="btn btn-success btn-sm disabled">Processed</button>
                                      @endif
                                    @endif

                                    @if($r->is_deleted=='1')
                                    <a  href="{{ url("user/deletefile/$r->id") }}" class="btn btn-danger btn-sm" id="process">Delete file</a>
                                    @else
                                    <label class="label label-success">File deleted</label>
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
<script type="text/javascript">
  $("#myform").on("submit", function(){
    $("#submit").hide();
    $("#reset").hide();
    $("#loader").show();
  });
  $("#process").on("click",function(){
    $("#process").hide();
    $("#loader").show();
  }); 
</script>
<script type="text/javascript">
  $('#datatables').DataTable();
</script>
<script>
function myFunction() {
  confirm("Press a button!");
}
</script>
<script type="text/javascript">

  $('#checkboxselect').on("click",function(){
    $("#buttonnew").show();
  })

 async function check(){
  $("#buttonnew").hide();
  $("#loader").show();

    var grid = document.getElementById("datatables");
    var checkBoxes=grid.getElementsByTagName("INPUT");
    var id = "";
    for (var i = 0; i < checkBoxes.length; i++) {
            if (checkBoxes[i].checked) {
                var row = checkBoxes[i].parentNode.parentNode;
                id = row.cells[1].innerHTML;
               await callfunction(id);

            }
        }      
}

async function callfunction(id){
  $.ajax({
          url: '{{ url('user/processbulkfile') }}/'+id,
          type: 'get',
          dataType: "JSON",
          data: {
          "id": id,
          "_token":"{{ csrf_token() }}"
          },
                });
}
</script>

<script type="text/javascript">
  $("#checkAll").click(function(){
    $('input:checkbox').not(this).prop('checked', this.checked);
});
</script>

@endpush
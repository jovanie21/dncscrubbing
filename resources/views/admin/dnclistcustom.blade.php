@extends('admin.layout.app')
@section('title','Do Not Contact')
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
                    <h4 class="page-title">Do not Call List </h4>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <!-- end row -->
         <div class="row">
        <div class="col-sm-12">
          <div class="card-box">
            <h4 class="text-danger">Enter Mobile Number to check whether the number is in DNC or not</h4>
            <form method="post" action="{{ url('admin/findnumber') }}" class="form-validate-summernote"  enctype="multipart/form-data">
              @csrf
              <div class="row form-group">   
                <div class="col-sm-6">
                  <label class="">Mobile Number<i class="text-danger ">*</i></label>
                  <input type="text" class="form-control number" name="phone" required=""  minlength="10" maxlength="10" value="{{ old('phone') }}">
                  <div class="text-danger">{{ $errors->first('phone') }}</div>
                </div>
                <br>
                  <div class="col-sm-6">
                    <input type="submit" name="submit" class="btn btn-sm btn-primary" value="Submit">
                    <input type="reset" name="reset" value="Cancel" class="btn btn-sm btn-default">
                  </div>
              </div>
            </form>
          </div>
        </div>
      </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="card-box">
                  <form method="post" action="{{ url('admin/findpage') }}" class="form-validate-summernote"  enctype="multipart/form-data">
              @csrf
              <div class="row form-group">   
                <div class="col-xs-12">
                    <div class="col-sm-2">
                  <label class="">Page Number<i class="text-danger ">*</i></label>
                  <input type="number" class="form-control" name="page" required="" max="10000000" min="1" value="{{ old('page') }}" placeholder="1">
                  <div class="text-danger">{{ $errors->first('page') }}</div>
                </div> 
                <div class="col-sm-2">
                  <label class="">Number of Records<i class="text-danger ">*</i></label>
                  <input type="number" class="form-control number" name="records" max="100" maxlength="3" min="1" required="" value="{{ old('records') }}" placeholder="10">
                  <div class="text-danger">{{ $errors->first('records') }}</div>
                </div>
                 <br>
                  <div class="col-sm-2">
                    <input type="submit" name="submit" class="btn btn-sm btn-primary" value="Submit">
                    <input type="reset" name="reset" value="Cancel" class="btn btn-sm btn-default">
                  </div>
                </div>
              </div>
            </form> 
            <h5 class="text-danger text-center">Showing Data of {{ $page }} Page with {{ $records }} Records</h5>
                   <div class="table-responsive">
                    <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>S.NO.</th>
                                <th>Phone Number</th>     
                                <th>Federal</th>              
                                <th>Litigator</th>              
                                <th>Internal</th>              
                                <th>Wireless</th>              
                                <th>Uploaded By</th>              
                                <th>Modified By</th>             
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dncdata as $data)
                            <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $data->phone_no}}</td>
                        <td>{{ $data->federal}}</td>
                        <td>{{ $data->litigator}}</td>
                        <td>{{ $data->internal}}</td>
                        <td>{{ $data->wireless}}</td>
                        <td>{{ $data->uploaded_by}}</td>
                        <td>{{ $data->modified_by}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div> <!-- container -->
</div> <!-- content -->
</div>
@endsection
@push('footerscript')<!-- 
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
            searching: false,
            ajax: {
                url: '{{ url('admin/getData') }}',
                data: function (d) {
                    d.filter_date = $('#filter_date').val();
                }
            },
            columns: [
            { data: 'rownum', name: 'rownum', searchable: false},              
            { data: 'phone_no', name: 'phone_no' },          
            { data: 'federal', name: 'federal' },       
            { data: 'litigator', name: 'litigator' },       
            { data: 'internal', name: 'internal' },       
            { data: 'wireless', name: 'wireless' },       
            { data: 'uploaded_by', name: 'uploaded_by' }, 
            { data: 'modified_by', name: 'modified_by' },       
            { data: 'created_at', name: 'created_at' },       
            { data: 'updated_at', name: 'updated_at' },       

            ]
        });
    });
</script>
<script type="text/javascript">
    $('#filter_date').on('change', function () {
        $('#datatables').DataTable().draw(false)
    });
</script> -->
@endpush
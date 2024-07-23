@extends('user.layout.app')
@section('title','DNC LIST')
@push('headerscript')
<link href="{{ asset('theme/plugins/bootstrap-sweetalert/sweet-alert.css') }}" rel="stylesheet" type="text/css">
<style type="text/css">

.pagination{
    float: right!important;
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
            <div class="col-xs-12">
                <div class="card-box">
                    <form method="post" action="{{ url('user/findpage') }}" class="form-validate-summernote"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row form-group">
                            <div class="col-xs-12">
                                <div class="col-sm-2">
                                    <label class="">Page Number<i class="text-danger ">*</i></label>
                                    <input type="number" class="form-control number" name="page" required=""
                                        max="10000000" min="1" value="{{ old('page') }}" maxlength="7"
                                        placeholder="1">
                                    <div class="text-danger">{{ $errors->first('page') }}</div>
                                </div>
                                <div class="col-sm-2">
                                    <label class="">Number of Records<i class="text-danger ">*</i></label>
                                    <input type="number" class="form-control" name="records" required=""
                                        max="100" min="1" maxlength="3" value="{{ old('records') }}"
                                        placeholder="10">
                                    <div class="text-danger">{{ $errors->first('records') }}</div>
                                </div>
                                <br>
                                <div class="col-sm-2" style="margin-top: 10px;">
                                    <input type="submit" name="submit" class="btn btn-sm btn-primary"
                                        value="Submit">
                                    <input type="reset" name="reset" value="Cancel"
                                        class="btn btn-sm btn-default">
                                </div>
                            </div>
                        </div>
                    </form>
                    
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
                        width="100%" id="">
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
                                @foreach ($dnc as $key =>  $data)
                                    <tr>
                                        <td>{{ $dnc->firstItem() + $key }}</td>
                                        <td>{{ $data->phone_no }}</td>
                                        <td>{{ $data->federal }}</td>
                                        <td>{{ $data->litigator }}</td>
                                        <td>{{ $data->internal }}</td>
                                        <td>{{ $data->wireless }}</td>
                                        <td></td>
                                        <td>{{ $data->updated_at->setTimezone('America/New_York') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-center">
                            {{ $dnc->links() }}
                        </div>
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

@endpush
@extends('admin.layout.app')
@section('title', 'Do Not Contact')
@push('headerscript')
    <link href="{{ asset('theme/plugins/bootstrap-sweetalert/sweet-alert.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('theme/plugins/datatables/responsive.bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
<style>
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
                @if (session()->has('success'))
                    <div class="alert alert-success">
                        {{ session()->get('success') }}
                    </div>
                @endif
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">
                            <h4 class="text-danger">Enter Mobile Number to check whether the number is in DNC or not</h4>
                            <form method="post" action="{{ url('admin/findnumber') }}" class="form-validate-summernote"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="region" id="region" value="{{ request()->region }}">
                                <div class="row form-group">
                                    <div class="col-sm-6">
                                        <label class="">Mobile Number<i class="text-danger ">*</i></label>
                                        <input type="text" class="form-control number" placeholder="Enter Mobile Number"
                                            name="phone" required="" minlength="10" maxlength="10"
                                            value="{{ old('phone') }}">
                                        <div class="text-danger">{{ $errors->first('phone') }}</div>
                                    </div>
                                    <br>
                                    <div class="col-sm-6" style="margin-top: 9px;">
                                        <input type="submit" name="submit" class="btn btn-sm btn-primary" value="Search">
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
                            <form method="post" action="{{ url('admin/findpage') }}" class="form-validate-summernote"
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
                            <div class="row form-group">
                                <div class="col-xs-12">
                                    <div class="col-sm-2">
                                        <label class="">Fillter<i class="text-danger ">*</i></label>
                                    </div>
                                    <div class="col-sm-2">
                                        <select name="option" id="option" class="form-control" onchange="fillter()" required>
                                            <option value="">Select Option</option>
                                            @foreach($usersWithCompany as $val)
                                            <option value="{{ $val->user_id }}" {{ (isset($_GET['option']) && $_GET['option'] == $val->user_id)?'selected':'' }}>{{ $val->company_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

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
                                            @php
                                                $user = App\Models\User::where('id',$data->uploaded_by)->first('name');
                                                    
                                            @endphp
                                            <tr>
                                                <td>{{ $dnc->firstItem() + $key }}</td>
                                                <td>{{ $data->phone_no }}</td>
                                                <td>{{ $data->federal }}</td>
                                                <td>{{ $data->litigator }}</td>
                                                <td>{{ $data->internal }}</td>
                                                <td>{{ $data->wireless }}</td>
                                                <td>{{ $user->name }}</td>
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
            </div> <!-- container -->
        </div> <!-- content -->
    </div>
@endsection
@push('footerscript')
    
    <script src="{{ asset('theme/plugins/bootstrap-sweetalert/sweet-alert.min.js') }}"></script>
    <script>
        function fillter() {
            var selectedOption = $('#option').val();
            window.location = "{{ url('admin/dnclist') }}?option="+selectedOption;
        }    
    </script>
@endpush

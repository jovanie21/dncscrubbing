@extends('admin.layout.app')
@section('title', 'Scrub Upload Files')
@push('headerscript')
    <link href="{{ asset('theme/plugins/bootstrap-sweetalert/sweet-alert.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('theme/plugins/datatables/responsive.bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('theme/plugins/datatables/dataTables.bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
                            <h4 class="page-title">Scrub Upload File</h4>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <!-- end row -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box" id="uploadhide">
                            <form method="post" action="{{ url('comparenondncfile/compare') }}" class="form-validate-summernote"
                                id="myform" enctype="multipart/form-data">
                                @csrf
                                <div class="row form-group">
                                    <div class="col-sm-2">
                                        <label class="">Upload(Browse File)<i class="text-danger ">*</i></label>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="file" name="file1" class="form-control" required="">
                                        <div class="text-danger">{{ $errors->first('file1') }}</div>
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col-sm-2">
                                        <label class="">Upload(Browse Compare File)<i class="text-danger ">*</i></label>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="file" name="file2" class="form-control" required="">
                                        <div class="text-danger">{{ $errors->first('file2') }}</div>
                                    </div>
                                </div>
                               
                                
                                <div class="row form-group">
                                    <div class="col-sm-12">
                                        <div class="col-sm-10">
                                            <input type="submit" id="submit" name="submit"
                                                class="btn btn-sm btn-primary" value="Submit">
                                            <input type="reset" id="reset" name="reset" value="Cancel"
                                                class="btn btn-sm btn-default">
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
              
            </div> <!-- container -->
        </div> <!-- content -->
       
    </div>
   
@endsection
@push('footerscript')
    <script src="{{ asset('theme/plugins/bootstrap-sweetalert/sweet-alert.min.js') }}"></script>
    <script src="{{ asset('theme/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('theme/plugins/datatables/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('theme/plugins/datatables/dataTables.bootstrap.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script type="text/javascript">
        $('#datatables').dataTable();
        $('select.select2').select2();
        var regions = $('#regions').data('content');
    </script>
   
@endpush

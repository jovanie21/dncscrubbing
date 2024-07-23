@extends('admin.layout.app')
@section('title', 'Upload Files')
@push('headerscript')
    <link href="{{ asset('theme/plugins/summernote/summernote.css') }}" rel="stylesheet" />

    <style>
        .loader {
            border: 16px solid #ccc;
            border-radius: 100%;
            border-top: 16px solid #3498db;
            width: 120px;
            height: 120px;
            -webkit-animation: spin 2s linear infinite;
            /* Safari */
            animation: spin 2s linear infinite;
        }

        /* Safari */
        @-webkit-keyframes spin {
            0% {
                -webkit-transform: rotate(0deg);
            }

            100% {
                -webkit-transform: rotate(360deg);
            }
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        div.dataTables_wrapper div.dataTables_processing {
            top: 0;
            color: red;
        }
    </style>
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
                            <h4 class="page-title">Upload DNC File</h4>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <!-- end row -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box" id="uploadhide">
                            <form method="post" action="{{ url('admin/uploadadminfile') }}"
                                class="form-validate-summernote" id="myform" enctype="multipart/form-data">
                                @csrf
                                <div class="row form-group">
                                    <div class="col-sm-2">
                                        <label class="">Select List<i class="text-danger ">*</i></label>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="row">
                                                <span class="col-sm-4">
                                                    <input type="radio" name="is_existing_file" id="" value="1" class="is_existing_file" checked>
                                                    <label for="internal"> Update Existing List</label>
                                                </span>
                                                <span class="col-sm-4">
                                                    <input type="radio" name="is_existing_file" id="" value="0" class="is_existing_file">
                                                    <label for="internal">New List</label>
                                                </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-sm-2">
                                        <label class="">DNC List Name<i class="text-danger ">*</i></label>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" name="name" value="{{ old('name') }}"
                                            class="form-control" required="" placeholder="DNC List Name" style="display: none;" id="new_file" disabled>

                                            <select class="form-control"  name="region_id" id="existing_file1">
                                            <option value="" disabled selected >--Select DNC List--</option>
                                            @foreach ($regions as $region)
                                                <option value="{{ $region->id }}">{{ $region->name }}</option>
                                            @endforeach
                                            </select>
                                        <div class="text-danger">{{ $errors->first('name') }}</div>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-sm-2">
                                        <label class="">Upload DNC File<i class="text-danger ">*</i></label>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="file" name="file" class="form-control" required="">
                                        <div class="text-danger">{{ $errors->first('file') }}</div>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-sm-2">
                                        <label class="">List Type <i class="text-danger ">*</i></label>
                                    </div>
                                    <div class="col-sm-6">
                                        <select class="form-control select2" multiple name="contact_type[]" required>
                                            <option>--Select List Type--</option>
                                            <option value="federal">Federal</option>
                                            <option value="litigator">Litigator</option>
                                            <option value="internal">Internal</option>
                                            <option value="wireless">Wireless</option>
                                        </select>
                                        <div class="text-danger">{{ $errors->first('file') }}</div>
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
                <div class="container" id="progress" style="display: none">
                    <h1><span id="done"></span><span> Uploading </span></h1>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="page-title-box">
                        <h4 class="page-title text-primary">DNC Region Wise Files</h4>
                        <div class="clearfix"></div>
                    </div>
                    <div class="col-xs-12">
                        <div class="card-box">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
                                    width="100%" id="datatables">
                                    <thead>
                                        <tr>
                                            <th>S.NO.</th>
                                            <th>Name</th>
                                            <th>Status</th>
                                            <th>Uploaded Date</th>
                                            <th>Finished time</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($imports as $import)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $import->name }}</td>
                                                <td class="text-center">
                                                    <label
                                                        class="label label-{{ $import->status == 'processed' ? 'primary' : ($import->status == 'processing' ? 'info' : 'danger') }}">{{ ucwords($import->status) }}</label>
                                                </td>
                                                <td>
                                            
                                                    {{ date('m/d/Y h:i:s', strtotime($import->created_at->setTimezone('America/New_York'))) }}
                                                </td>
                                                <td>
                                                    {{ date('m/d/Y h:i:s', strtotime($import->updated_at->setTimezone('America/New_York'))) }}
                                                </td>
                                                <td>
                                                    <a href="{{ $import->status == 'processed' ? url('admin/dnclist') . '?region=' . $import->name : '#' }}"
                                                        class="btn btn-primary">
                                                        View
                                                    </a>
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
    <script src="{{ asset('theme/plugins/bootstrap-sweetalert/sweet-alert.min.js') }}"></script>
    <script src="{{ asset('theme/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('theme/plugins/datatables/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('theme/plugins/datatables/dataTables.bootstrap.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script type="text/javascript">
        $("#myform").on("submit", function() {
            $("#submit").hide();
            $("#reset").hide();
            $("#loader").show();
        });
        $("#process").on("click", function() {
            $("#process").hide();
            $("#loader").show();
        });
        $('select.select2').select2();
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
        $('#checkboxselect').on("click", function() {
            $("#buttonnew").show();
        })
        $(document).ready(function() {
            $('.is_existing_file').click(function(){
                if(this.value=='1')
                {
                    $('#existing_file1').show()
                    $('#new_file').attr('disabled','disabled').hide()  
                }else{    
                    $('#existing_file1').css('display','none')
                    $('#new_file').show().removeAttr('disabled');
                }

            });
            $("#buttonnew").on("click", async function() {
                $("#buttonnew").prop("disabled", true);
                $("#buttonnew").text("Processing...");
                var grid = document.getElementById("datatables");
                var checkBoxes = grid.getElementsByTagName("INPUT");
                var id = "";
                let checkboxIndex = 0;
                for (const checkBox of checkBoxes) {
                    console.log(checkboxIndex);
                    if (checkBox.checked) {
                        var row = checkBox.parentNode.parentNode;
                        id = row.cells[1].innerHTML;
                        await callfunction(id, checkboxIndex);
                    }
                    checkboxIndex++;

                }
                $("#buttonnew").prop("disabled", false);
                $("#buttonnew").text("Bulk Process");
            })
        })

        async function check() {}


        async function callfunction(id, i) {

            console.log(id, i);
            await $.ajax({
                url: '{{ url('admin/processabulkdminfile') }}/' + id,
                type: 'get',
                // dataType: "JSON",
                async: true,
                data: {
                    "id": id,
                    "_token": "{{ csrf_token() }}"
                },
                success: function(data) {
                    console.log(data);
                    if (data = 1) {
                        var chkPassport = document.getElementById("checkAll");
                        if (chkPassport.checked) {
                            var checkboxcheck = document.querySelectorAll('input[type="checkbox"]:checked')
                                .length;
                            var checkedcheck = checkboxcheck - 2;
                        } else {
                            var checkboxcheck = document.querySelectorAll('input[type="checkbox"]:checked')
                                .length;
                            var checkedcheck = checkboxcheck - 1;
                        }
                        // document.getElementById("done").innerHTML = i+1;
                        /*                   if (checkedcheck == i) {
                                               location.reload();
                                           }*/
                    }
                }
            });
        }
        /*		
                    $.ajax({
                        url: '{{ url('admin/processabulkdminfile') }}/' + id,
                        type: 'get',
                        dataType: "JSON",
                        data: {
                            "id": id,
                            "_token": "{{ csrf_token() }}"
                        },
                    , success:(function(data) {
                        if (data = 1) {
                            var chkPassport = document.getElementById("checkAll");
                            if (chkPassport.checked) {
                                var checkboxcheck = document.querySelectorAll('input[type="checkbox"]:checked').length;
                                var checkedcheck = checkboxcheck - 2;
                            } else {
                                var checkboxcheck = document.querySelectorAll('input[type="checkbox"]:checked').length;
                                var checkedcheck = checkboxcheck - 1;
                            }
                            // document.getElementById("done").innerHTML = i+1;
                            if (checkedcheck == i) {
                                location.reload();
                            }
                        }
                    })
        		}
        	*/


        /*            ajaxReq.success(function(data) {
                        if (data = 1) {
                            var chkPassport = document.getElementById("checkAll");
                            if (chkPassport.checked) {
                                var checkboxcheck = document.querySelectorAll('input[type="checkbox"]:checked').length;
                                var checkedcheck = checkboxcheck - 2;
                            } else {
                                var checkboxcheck = document.querySelectorAll('input[type="checkbox"]:checked').length;
                                var checkedcheck = checkboxcheck - 1;
                            }
                            // document.getElementById("done").innerHTML = i+1;
                            if (checkedcheck == i) {
                                location.reload();
                            }
                        }
                    })*/
    </script>
    <script type="text/javascript">
        $("#checkAll").click(function() {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
    </script>
@endpush

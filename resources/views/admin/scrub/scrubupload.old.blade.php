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
                            <form method="post" action="{{ url('admin/storescrubfile') }}" class="form-validate-summernote"
                                id="myform" enctype="multipart/form-data">
                                @csrf
                                <div class="row form-group">
                                    <div class="col-sm-2">
                                        <label>Scrub Type</label>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="row">
                                            {{-- <span class="col-sm-2">
                                                <input type="radio" name="type" id="global" value="global"
                                                    {{ auth()->user()->name == 'Company' ? '' : 'checked' }}>
                                                <label for="global">Global</label>
                                            </span>
                                            @if (auth()->user()->name == 'Company')
                                                <span class="col-sm-2">
                                                    <input type="radio" name="type" id="internal" value="internal">
                                                    <label for="internal">Internal</label>
                                                </span>
                                            @endif --}}
                                            <span class="col-sm-2">
                                                <input type="checkbox" name="type1[]" id="internal" value="internal">
                                                <label for="internal">Internal</label>
                                            </span>
                                            <span class="col-sm-2">
                                                <input type="checkbox" name="type1[]" id="federal" value="federal">
                                                <label for="federal">Federal</label>
                                            </span>
                                            <span class="col-sm-2">
                                                <input type="checkbox" name="type1[]" id="litigator" value="litigator">
                                                <label for="litigator">Litigator</label>
                                            </span>
                                            <span class="col-sm-2">
                                                <input type="checkbox" name="type1[]" id="wireless" value="wireless">
                                                <label for="wireless">Wireless</label>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-sm-2">
                                        <label class="">Upload Name<i class="text-danger ">*</i></label>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" name="name" value="{{ old('name') }}"
                                            class="form-control" required="" placeholder="Upload Name">
                                        <div class="text-danger">{{ $errors->first('name') }}</div>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-sm-2">
                                        <label class="">Upload(Browse File)<i class="text-danger ">*</i></label>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="file" name="file" class="form-control" required="">
                                        <div class="text-danger">{{ $errors->first('file') }}</div>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-sm-2">
                                        <label class="">DNC List</label>
                                    </div>
                                    <div class="col-sm-6">
                                        <select name="region[]" id="region" class="form-control select2 w-100"
                                            multiple="multiple" >
                                            <!-- <option value="">Select Regions</option> -->
                                            @foreach ($regions as $region)
                                                <option value="{{ $region->id }}">{{ $region->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="text-danger">{{ $errors->first('file') }}</div>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-sm-2">
                                        <label for="">Scrubing Option<i class="text-danger ">*</i></label>
                                    </div>
                                    <div class="col-sm-6">
                                        <select name="option" id="option" class="form-control" required>
                                            <option value="">Select Option</option>
                                            <option value="combined">Combined</option>
                                            <option value="seperate">Seperate (DNC or NON-DNC)</option>
                                        </select>
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
                <div class="container">
                    <div class="row">
                        <div class="page-title-box">
                            <h4 class="page-title text-primary">Uploaded File</h4>
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
                                                <th>Processed</th>
                                                <th>Uploaded Date</th>
                                                <th>Finished time</th>
                                                <th>Action</th>
                                                <th>Receipt</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($exports as $export)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $export->name }}</td>
                                                    <td class="text-center">
                                                        <label
                                                            class="label label-{{ $export->status == 'processed' ? 'success' : ($export->status == 'processing' ? 'info' : 'danger') }}">{{ ucwords($export->status) }}</label>
                                                    </td>
                                                    <td>
                                                        {{ date('m/d/Y h:i:s', strtotime($export->created_at->setTimezone('America/New_York'))) }}
                                                    </td>
                                                    <td>
                                                        @if($export->status === 'processed')
                                                        {{ date('m/d/Y h:i:s', strtotime($export->updated_at->setTimezone('America/New_York'))) }}
                                                        @endif 

                                                    </td>
                                                    <td class="text-center">
                                                        @if (!count(array_filter(array_keys($export->paths), 'is_string')) > 0)
                                                            <button
                                                                class="btn {{ $export->status == 'processed' ? 'btn-primary modalBtn' : 'btn-disabled' }}"
                                                                type="button" data-regions="{{ $export->regions }}"
                                                                style="{{ $export->status == 'processed' ? '' : 'cursor: not-allowed' }}"
                                                                {{ $export->status == 'processed' ? '' : 'disabled' }}
                                                                data-content="{{ json_encode($export->paths) }}">
                                                                <i class="fa fa-info"></i>
                                                            </button>
                                                        @else
                                                            <span style="display:flex; justify-content: space-evenly;">
                                                                <a class="btn btn-success"
                                                                    href="{{ $export->status == 'processed' ? asset('dnc-seperated/' . $export->paths['active']) : '' }}">DNC</a>
                                                                <a class="btn btn-danger"
                                                                    href="{{ $export->status == 'processed' ? asset('dnc-seperated/' . $export->paths['inactive']) : '' }}">NON-DNC</a>
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{ url('admin/pdf/' . $export->id . '') }}"
                                                            class="btn btn-sm btn-success" target="_blank">
                                                            Genrate Pdf
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
        <div class="modal fade" id="pathsModal" tabindex="-1" aria-labelledby="pathsModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="pathsModalLabel">Download Files</h5>
                    </div>
                    <div class="modal-body">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <button type="button" class="d-none" id="modalBtn" data-toggle="modal" data-target="#pathsModal"></button>
    <div class="" id="regions" data-content="{{ json_encode($regions) }}"></div>
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
    <script>
        function deleteit(id) {
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
                function(isConfirm) {
                    if (isConfirm) {

                        $('#datatables').dataTable().draw(false);
                        swal("Deleted!", "User has been deleted!", "success");
                        //window.location.reload();
                    } else {
                        swal("Cancelled", "User data is safe :)", "error");
                    }

                });

        }
    </script>
    <!-- <script>
        setInterval(function() {
            location.reload();
        }, 60000);
    </script> -->

    <!-- <script>
        setInterval(function() {
            location.reload();
        }, 120000);

        @if (session('success_msg'))
            alert('{{ session('success_msg') }}');
        @endif
    </script> -->

    <script>
        $(document).on('click','.modalBtn',function() {
            var content = $(this).data('content');
            var html = '';
            console.log(content, regions);
            for (i in content) {
                html +=
                    `<div class="row"><div class="col-sm-6"><h5>${regions[(i-1)]?.name}</h5></div><div class="col-sm-6" style="display:flex; justify-content: space-evenly;"><a class="btn btn-success" href="{{ asset('dnc-seperated/${content[i]?.active}') }}">DNC</a><a class="btn btn-danger" href="{{ asset('dnc-seperated/${content[i]?.inactive}') }}">NON-DNC</a></div></div>`
            }
            html += '</div>';
            $('#pathsModal').find('.modal-body').html(html);
            $('#modalBtn').click();
        });
    </script>
@endpush

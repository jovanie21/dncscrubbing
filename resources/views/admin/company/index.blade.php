@extends('admin.layout.app')
@section('title', 'Clients')
@push('headerscript')
    <link href="{{ asset('theme/plugins/bootstrap-sweetalert/sweet-alert.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('theme/plugins/datatables/responsive.bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('theme/plugins/datatables/dataTables.bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
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
                            <h4 class="page-title">Company </h4>
                            <a href="{{ route('company.create') }}" class="btn btn-primary btn-sm pull-right"><i
                                    class="fa fa-plus"></i> Add New Company</a>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <!-- end row -->
                <div class="row">

                    <div class="col-xs-12">
                        <div class="card-box">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
                                    width="100%" id="datatables">
                                    <thead>
                                        <tr>
                                            <th>S.NO.</th>
                                            <th>Company Name</th>
                                            <th>Email ID</th>
                                            <th>Phone Number</th>
                                            <th>Company Zip</th>
                                            <th>Created At</th>
                                            <th>Updated_at</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    @foreach ($usersWithCompanyDetails as $key => $user)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $user->company_name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->phone_number }}</td>
                                            <td>{{ $user->company_zip }}</td>
                                            <td>{{ $user->created_at->setTimezone('America/New_York') }}</td>
                                            <td>{{ $user->updated_at->setTimezone('America/New_York') }}</td>
                                            <td><a href="{{ route('company.edit', $user->user_id) }}"
                                                    class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i>
                                                    Edit</a>&nbsp&nbsp
                                                    <a href="{{ route('changepass', $user->user_id) }}"
                                                        class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-wrench"></i>
                                                        Password</a>&nbsp&nbsp
                                                <button class="btn btn-xs btn-danger"
                                                    onclick="deleteit({{ $user->user_id }})"><i
                                                        class="glyphicon glyphicon-trash"></i> Delete</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
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
    <script>
        $(document).ready(function() {
            $('#datatables').DataTable();
        });

        function deleteit(userId) {
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
                        $.ajax({
                            url: '{{ url('admin/company') }}/' + userId,
                            type: 'delete',
                            dataType: "JSON",
                            data: {
                                "id": userId,
                                "_token": "{{ csrf_token() }}"
                            },
                            success: function(response) {
                                swal("Deleted!", "Company has been deleted!", "success");
                                location.reload();
                            },
                            error: function(xhr) {
                                swal("Error", "Could not delete company", "error");
                            }
                        });
                    } else {
                        swal("Cancelled", "User data is safe :)", "error");
                    }
                });
        }
    </script>
@endpush

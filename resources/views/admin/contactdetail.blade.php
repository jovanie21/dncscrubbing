@extends('admin.layout.app')
@section('title','Contact')
@push('headerscript')
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
                    <h4 class="page-title">Contact Details of <span class="text-info">{{ $contact->first_name}} {{ $contact->last_name }}</span> </h4>

                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <!-- end row -->
        <div class="card-box" style="line-height: 2; font-size: 16px;">
            <div class="row">
                <div class="col-xs-12 col-sm-12">

                    <div class="col-sm-2"><strong>Name</strong></div>
                    <div class="col-sm-10">{{ $contact->first_name }} {{ $contact->last_name }} </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-xs-12 col-sm-12">
                    
                    <div class="col-sm-2"><strong>Phone Number</strong> </div>
                    <div class="col-sm-10">{{ $contact->phone_no }}</div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-xs-12 col-sm-12">
                    
                    <div class="col-sm-2"><strong>Email ID</strong> </div>
                    <div class="col-sm-10">{{ $contact->email }}</div>
                </div>
            </div>
            <hr>
            <div class="row" >
                <div class="col-xs-12 col-sm-12">
                    <div class="col-sm-2"><strong>Message</strong></div>
                    <div class="col-sm-10" style="background-color:blue; color: #fff; ">{{ $contact->message }}</div>
                </div>
            </div>            
        </div>

            <div class="row" >
                <div class="col-xs-12 col-sm-12">
                   <a href="{{ url('admin/viewcontact') }}" class="btn btn-primary btn-sm"><i class="fa fa-backward"></i> Previous Screen</a>
                </div>
            </div>
    </div> <!-- container -->
</div> <!-- content -->
</div>

@endsection
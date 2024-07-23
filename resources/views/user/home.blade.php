@extends('user.layout.app')
@section('title','Welcome User')
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
                        <h4 class="page-title">Welcome <span class="text-primary">{{ strToUpper(Auth::user()->name) }}</span> </h4>
                        <div class="clearfix"></div>
                    </div>
				</div>
			</div>
            <!-- end row -->
            <div class="row">
                <div class="col-xs-12">
                    <div class="card-box">
                       <div class="row">

                            <div class="col-lg-4 col-md-6">
                                <a href="{{ url('user/dncuserlist') }}" target="_blank">
                                <div class="card-box widget-box-two widget-two-primary">
                                    <i class="mdi mdi-chart-areaspline widget-two-icon"></i>
                                    <div class="wigdet-two-content">
                                        <p class="m-0 text-uppercase font-600 font-secondary text-overflow" title="Statistics">Total Contacts</p>
                                        <h2><span data-plugin="counterup">{{ $total_contact }}</span>  </h2>
                                    </div>
                                </div>
                            </a>
                            </div><!-- end col -->
                        </div>
                        <!-- end row -->
                    </div>
                </div>
            </div>

        </div> <!-- container -->
    </div> <!-- content -->
    @endsection

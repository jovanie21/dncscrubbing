@extends('layouts.app')
@section('title','Login')
@section('content')
<!-- HOME -->
<div class="video-w3l" >
    <!--header-->
    @if(session()->has('error'))
    <div class="alert alert-danger">
        {{ session()->get('error') }}
    </div>
    @endif
    <br><br><br>
    <div class="header-w3l" style="margin-left:38% ;">
        <img src="{{asset('webtheme/img/logo.png')}}" alt="logo" class="img-fluid"/>
    </div><br><br>
    <!--//header-->
    <div class="main-content-agile">
        <div class="sub-main-w3 temp text-center" style="max-width: 600px;   height: 400px; min-height: 330px; padding: 20px 20px; margin: 40px auto; box-shadow: 0px 0px 11px 13px rgba(0,0,0,0.75);">
            <h1 style="color: white;">Change Password
                <i class="fa fa-hand-o-down" aria-hidden="true"></i>
            </h1>
            <br><br>
            <form method="POST" action="{{ route('reset.password.post') }}" class="form-horizontal form-group">
                @csrf
            <input type="hidden" name="token" value="{{ $token }}">
               
             
                <div class="form-group">
                    <div class="col-xs-1">
                        <i class="fa fa-lock m-r-5 text-info" style="font-size:x-large;"></i>
                       <!-- <span class="fa fa-key text-info" aria-hidden="true" style="font-size:x-large;"></span> -->
                    </div>
                    <div class="col-xs-11">
                        <input name="password" id="password-field" class="form-control" type="password" required="" placeholder="Password">
                        <span toggle="#password-field" class="fa fa-fw fa-eye-slash field-icon toggle-password" style="float: right; margin-left: -25px;margin-right: 10px; margin-top: -25px; position: relative; z-index: 2; color: black "></span>
                        @if ($errors->has('password_confirmation'))
                        <div class="text-danger">{{$errors->first('password')}}</div>
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-1">
                        <i class="fa fa-lock m-r-5 text-info" style="font-size:x-large;"></i>
                       <!-- <span class="fa fa-key text-info" aria-hidden="true" style="font-size:x-large;"></span> -->
                    </div>
                    <div class="col-xs-11">
                        <input name="password_confirmation" id="password-field2" class="form-control" type="password" required="" placeholder="Confirm Password">
                        <span toggle="#password-field2" class="fa fa-fw fa-eye-slash field-icon toggle-password1" style="float: right; margin-left: -25px;margin-right: 10px; margin-top: -25px; position: relative; z-index: 2; color: black "></span>
                        @if ($errors->has('password_confirmation'))
                        <div class="text-danger">{{$errors->first('password_confirmation')}}</div>
                        @endif
                    </div>
                </div>
               
		      
                <div class="form-group account-btn text-center m-t-10">
                    <div class="col-xs-12">
                        <button class="btn w-md btn-bordered btn-default waves-effect waves-light" type="submit">Submit</button>
                    </div>
                </div>
            </form>
            <br />
            <form action="login/admin_login_reset" method="post">
                <div id="passrst1" style="display:none;">
                    <div class="pom-agile" >
                        <span class="fa fa-user-o" aria-hidden="true"></span>
                        <input placeholder="Enter Email to reset password" name="email" class="user" type="email" required="">
                    </div>
                    <div class="right-w3l">
                        <input type="submit" value="Reset">
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!--//main-->
    <!--footer-->

    <!--//footer-->
</div>

<script type="text/javascript" src="{{ asset('theme/plugins/d3/d3.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('theme/plugins/c3/c3.min.js')}}"></script>
<script src="{{ asset('theme/default/assets/pages/jquery.c3-chart.init.js')}}"></script>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
<script src="{{ asset('theme/plugins/select2/js/select2.js') }}"></script>
<script type="text/javascript" src="{{ asset('theme/plugins/moment/moment.js') }}"></script>
<script type="text/javascript"src="{{ asset('theme/plugins/bootstrap-daterangepicker/daterangepicker.js') }}"></script>

<script type="text/javascript">
    
  $(".toggle-password").click(function() {
  
      $(this).toggleClass("fa-eye fa-eye-slash");
      var input = $($(this).attr("toggle"));
      if (input.attr("type") == "password") {
        input.attr("type", "text");
      } else {
        input.attr("type", "password");
      }
      
    });
    $(".toggle-password1").click(function() {
  
        $(this).toggleClass("fa-eye fa-eye-slash");
      var input = $($(this).attr("toggle"));
      if (input.attr("type") == "password") {
        input.attr("type", "text");
      } else {
        input.attr("type", "password");
      }
      
    });

    

</script>
<!-- END HOME -->
@endsection




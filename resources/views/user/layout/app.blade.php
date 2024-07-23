<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Coderthemes">

    <!-- App favicon -->
    <!-- App title -->
    <title>@yield('title')</title>

    <!-- App css -->
    <link href="{{ asset('theme/default/assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('theme/default/assets/css/core.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('theme/default/assets/css/components.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('theme/default/assets/css/icons.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('theme/default/assets/css/pages.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('theme/default/assets/css/menu.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('theme/default/assets/css/responsive.css')}}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('theme/plugins/switchery/switchery.min.css')}}">
    @stack('headerscript')
</head>


<body class="fixed-left">

    <!-- Begin page -->
    <div id="wrapper">

        @include('user.layout.header')

       

        @yield('content')

        
    </div>


    <!-- ============================================================== -->
    <!-- End Right content here -->
    <!-- ============================================================== -->


    <!-- Right Sidebar -->
    
</div>
<!-- END wrapper -->



<script>
    var resizefunc = [];
</script>

<!-- jQuery  -->
<script src="{{ asset('theme/default/assets/js/jquery.min.js')}}"></script>
<script src="{{ asset('theme/default/assets/js/bootstrap.min.js')}}"></script>
<script src="{{ asset('theme/default/assets/js/detect.js')}}"></script>
<script src="{{ asset('theme/default/assets/js/fastclick.js')}}"></script>
<script src="{{ asset('theme/default/assets/js/jquery.blockUI.js')}}"></script>
<script src="{{ asset('theme/default/assets/js/waves.js')}}"></script>
<script src="{{ asset('theme/default/assets/js/jquery.slimscroll.js')}}"></script>
<script src="{{ asset('theme/default/assets/js/jquery.scrollTo.min.js')}}"></script>
<script src="{{ asset('theme/plugins/switchery/switchery.min.js')}}"></script>

<!-- App js -->
<script src="{{ asset('theme/default/assets/js/jquery.core.js')}}"></script>
<script src="{{ asset('theme/default/assets/js/jquery.app.js')}}"></script>
@stack('footerscript')
@include('user.layout.notificaton')
</body>
</html>
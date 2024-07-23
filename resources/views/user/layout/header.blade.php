<div class="topbar">
    <!-- LOGO -->
    <div class="topbar-left">
        <!-- <a href="#" class="logo"><span>DN<span>C</span></span><i class="mdi mdi-layers"></i></a>    -->
        <a href="#" class="logo">
            <span>
                <img src="{{ asset('webtheme/img/logo.png') }}" alt="" height="100%" width="100%">
            </span>
            <i>
                <img src="{{ asset('webtheme/img/logo.png') }}" alt="" height="13" width="50">
            </i>
        </a>
    </div>
    <!-- Button mobile view to collapse sidebar menu -->
    <div class="navbar navbar-default" role="navigation">
        <div class="container">

            <!-- Navbar-left -->
            <ul class="nav navbar-nav navbar-left">
                <li>
                    <button class="button-menu-mobile open-left waves-effect">
                        <i class="mdi mdi-menu"></i>
                    </button>
                </li>
            </ul>
            <!-- Right(Notification) -->
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown user-box">
                    <a href="#" class="dropdown-toggle waves-effect user-link" data-toggle="dropdown"
                        aria-expanded="true">
                        <img src="{{ asset('theme/default/assets/images/users/user.png') }}" alt="user-img"
                            class="img-circle user-img">
                    </a>
                    <ul
                        class="dropdown-menu dropdown-menu-right arrow-dropdown-menu arrow-menu-right user-list notify-list">
                        <li>
                            <h5>Hi,{{ auth()->user()->name == 'Company' ? auth()->user()->details->company_name : auth()->user()->name }}
                            </h5>
                        </li>
                        <li><a href="{{ url('user/profile') }}"><i class="ti-user m-r-5"></i> Profile</a></li>
                        <li><a href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i
                                    class="ti-power-off m-r-5"></i> Logout</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </li>
            </ul> <!-- end navbar-right -->
        </div><!-- end container -->
    </div><!-- end navbar -->
</div>
<!-- Top Bar End -->
<!-- ========== Left Sidebar Start ========== -->
<div class="left side-menu">
    <div class="sidebar-inner slimscrollleft">
        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <ul>
                <li>
                    <a href="{{ url('user/home') }}" class="waves-effect"><i
                            class="mdi mdi-view-dashboard"></i><span>Dashboard</span></a>
                </li>
                <!-- not required
                <li>
                    <a href="{{ url('user/dncuserlist') }}" class="waves-effect"><i class="mdi mdi-calendar"></i><span>DNC List</span></a>
                </li>
                -->
                @php
                    $check = env('APP_TYPE', '');
                @endphp
                @if ($check == 'DNC')
                    <li>
                        <a href="{{ url('user/upload') }}" class="waves-effect"><i
                                class="fa fa-upload"></i><span>Upload</span></a>
                    </li>
                @else
                <li>
                    <a href="{{ url('user/dnclist') }}" class="waves-effect"><i class="mdi mdi-calendar"></i><span>DNC
                            List</span></a>
                </li>
                    <li>
                        <a href="{{ url('user/upload') }}" class="waves-effect"><i class="mdi mdi-calendar"></i><span>
                            Internal DNC Upload</span></a>
                    </li>
                    <li>
                        <a href="{{ url('user/userscrub-upload') }}" class="waves-effect"><i
                                class="fa fa-upload"></i><span>DNC Scrub</span></a>
                    </li>
                @endif
                <!-- not required
                <li>
                    <a href="{{ url('user/tokendetail') }}" class="waves-effect"><i class="fa fa-ticket"></i><span>Token Details</span></a>
                </li>
                -->
            </ul>
        </div>
    </div>
    <!-- Sidebar -left -->
</div>
<!-- Left Sidebar End

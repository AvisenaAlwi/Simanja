<nav class="navbar navbar-vertical fixed-left navbar-expand-md navbar-light bg-white" id="sidenav-main"
style="box-shadow: 5px 0 20px 1px rgba(0,0,0,.15) !important">
    <div class="container-fluid">
        <!-- Toggler -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- Brand -->
        <a class="navbar-brand pt-0" href="{{ route('landingpage') }}">
            {{-- <img src="{{ asset('argon') }}/img/brand/blue.png" class="navbar-brand-img" alt="..."> --}}
            {{-- <span class="avatar avatar-sm rounded-circle"> --}}
            <h1>{{ config('app.name') }}</h1>
            {{-- </span> --}}
        </a>
        <style>
            .navbar-nav .nav-item{
                margin: .5rem .5rem;
                border-radius: 0px;
                transition: all .2s;
            }
            .navbar-nav .nav-item:not(.active).nav-item:hover{
                border-radius: 50px;
                box-shadow: 0 2px 10px 1px rgba(0, 0, 0, .1)
            }
            .navbar-nav > .active{
                border-radius: 50px;
                border: 2px solid #ffd32a;
                /* background: #fff; */
                color: white !important;
                box-shadow: 0 2px 20px 1px rgba(0, 0, 0, .1)
            }
        </style>
        <link href="{{ asset('vendor/bootstraptoggle') }}/bootstrap-toggle.min.css" rel="stylesheet">
        <!-- User -->
        <ul class="nav align-items-center d-md-none">
            <li class="nav-item dropdown">
                <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="media align-items-center">
                        {{-- <img alt="Image placeholder" src="{{ asset('argon') }}/img/theme/team-1-800x800.jpg"> --}}
                        {{ auth()->user()->name }}
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
                    <div class=" dropdown-header noti-title">
                        <h6 class="text-overflow m-0">Selamat Datang!</h6>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="dropdown-item">
                        <i class="ni ni-single-02"></i>
                        <span>Profil Saya</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                        <i class="ni ni-user-run"></i>
                        <span>Log Out</span>
                    </a>
                </div>
            </li>
        </ul>
        <!-- Collapse -->
        <div class="collapse navbar-collapse" id="sidenav-collapse-main">
            <!-- Collapse header -->
            <div class="navbar-collapse-header d-md-none">
                <div class="row">
                    <div class="col-6 collapse-brand">
                        <a href="{{ route('landingpage') }}">
                            {{-- <img src="{{ asset('argon') }}/img/brand/blue.png"> --}}
                            <h1>{{ config('app.name') }}</h1>
                        </a>
                    </div>
                    <div class="col-6 collapse-close">
                        <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle sidenav">
                            <span></span>
                            <span></span>
                        </button>
                    </div>
                </div>
            </div>
            {{-- <form class="mt-4 mb-3 d-md-none">
                <div class="input-group input-group-rounded input-group-merge">
                    <input type="search" class="form-control form-control-rounded form-control-prepended" placeholder="Cari" aria-label="Search">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <span class="fa fa-search"></span>
                        </div>
                    </div>
                </div>
            </form> --}}
            <!-- Navigation -->
            <ul class="navbar-nav">
                <li class="nav-item {{ $activeSideBar == 'dashboard' ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('dashboard') }}">
                        <i class="ni ni-tv-2 text-primary"></i> <b id="text">Dashboard</b>
                    </a>
                </li>
                <li class="nav-item {{ $activeSideBar == 'myactivity' ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('myactivity.index') }}">
                        <i class="ni ni-single-copy-04 text-danger"></i> <b id="text">Kegiatanku</b>
                    </a>
                </li>
                @if (auth()->user()->role_id != 3)
                <li class="nav-item {{ $activeSideBar == 'activity' ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('activity.index') }}">
                        <i class="ni ni-bullet-list-67 text-info"></i> <b id="text">Kegiatan</b>
                    </a>
                </li>
                <li class="nav-item {{ $activeSideBar == 'assignment' ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('assignment.index') }}">
                        <i class="fa fa-tasks text-warning"></i> <b id="text">Penugasan</b>
                    </a>
                </li>
                @endif
                <li class="nav-item {{ $activeSideBar == 'report' ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('report.index') }}">
                        <i class="ni ni-collection text-warning"></i> <b id="text">Pelaporan</b>
                    </a>
                </li>
                @if (auth()->user()->role_id == 1)
                <li class="nav-item {{ $activeSideBar == 'user' ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('user.index') }}">
                        <i class="ni ni-key-25 text-primary"></i> <b id="text">Manajemen Akun</b>
                    </a>
                </li>
                @endif
                <div class="d-flex justify-content-center p-4">
                    <input type="checkbox" data-toggle="toggle" data-width="300" {{ $Cookie::get('dark') == 'true' ? 'checked' : '' }}>
                </div>
                {{-- <li class="nav-item">
                    <a class="nav-link active" href="#navbar-examples" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="navbar-examples">
                        <i class="fab fa-laravel" style="color: #f4645f;"></i>
                        <span class="nav-link-text" style="color: #f4645f;">{{ __('Laravel Examples') }}</span>
                    </a>
                    <div class="collapse show" id="navbar-examples">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('profile.edit') }}">
                                    {{ __('User profile') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('user.index') }}">
                                    {{ __('User Management') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                </li> --}}

                {{-- <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="ni ni-planet text-blue"></i> {{ __('Icons') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="ni ni-pin-3 text-orange"></i> {{ __('Maps') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="ni ni-key-25 text-info"></i> {{ __('Login') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="ni ni-circle-08 text-pink"></i> {{ __('Register') }}
                    </a>
                </li> --}}
            </ul>
        </div>
    </div>
</nav>

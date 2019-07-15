<nav class="navbar navbar-vertical fixed-left navbar-expand-md navbar-light bg-white" id="sidenav-main">
    <div class="container-fluid">
        <!-- Toggler -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- Brand -->
        <a class="navbar-brand pt-0" href="{{ route('home') }}">
            {{-- <img src="{{ asset('argon') }}/img/brand/blue.png" class="navbar-brand-img" alt="..."> --}}
            {{-- <span class="avatar avatar-sm rounded-circle"> --}}
            <h1>{{ config('app.name') }}</h1>
            {{-- </span> --}}
        </a>
        <style>
        #asd{
            background-color: #89f7fe;
            background-image: linear-gradient(90deg, ##89f7fe, #66a6ff);
            padding: px;
            margin: 7px;
            border-top-left-radius: 80px;
            border-bottom-right-radius: 80px;
            border-bottom-style: outset;
            
        }
        #as{
            padding: 0px;
            margin: 7px 10px;
            border-radius: 20px 5px ;
            border-bottom-style: outset;
            font-size: 15px;
            color: #32325d;
        }
        #text{
            font-size: 15px;
            color: #32325d;
        }
        </style>
        <!-- User -->
        <ul class="nav align-items-center d-md-none">
            <li class="nav-item dropdown">
                <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="media align-items-center">
                        {{-- <img alt="Image placeholder" src="{{ asset('argon') }}/img/theme/team-1-800x800.jpg"> --}}
                        {{ config('app.name') }}
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
                    <div class=" dropdown-header noti-title">
                        <h6 class="text-overflow m-0">{{ __('Welcome!') }}</h6>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="dropdown-item">
                        <i class="ni ni-single-02"></i>
                        <span>{{ __('My profile') }}</span>
                    </a>
                    <a href="#" class="dropdown-item">
                        <i class="ni ni-settings-gear-65"></i>
                        <span>{{ __('Settings') }}</span>
                    </a>
                    <a href="#" class="dropdown-item">
                        <i class="ni ni-calendar-grid-58"></i>
                        <span>{{ __('Activity') }}</span>
                    </a>
                    <a href="#" class="dropdown-item">
                        <i class="ni ni-support-16"></i>
                        <span>{{ __('Support') }}</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                        <i class="ni ni-user-run"></i>
                        <span>{{ __('Logout') }}</span>
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
                        <a href="{{ route('home') }}">
                            <img src="{{ asset('argon') }}/img/brand/blue.png">
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
            <!-- Form -->
            <form class="mt-4 mb-3 d-md-none">
                <div class="input-group input-group-rounded input-group-merge">
                    <input type="search" class="form-control form-control-rounded form-control-prepended" placeholder="{{ __('Search') }}" aria-label="Search">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <span class="fa fa-search"></span>
                        </div>
                    </div>
                </div>
            </form>
            <!-- Navigation -->
            <ul class="navbar-nav">
                <li class="nav-item" id="as">
                    <a class="nav-link" href="{{ route('home') }}">
                        <i class="ni ni-tv-2 text-primary"></i> <b id="text">Dashboard</b>
                    </a>
                </li>
                <li class="nav-item" id="as">
                    <a class="nav-link" href="{{ route('activity.index') }}">
                        <i class="ni ni-bullet-list-67 text-blue"></i> <b id="text">Kegiatan</b>
                    </a>
                </li>
                <li class="nav-item" id="as">
                    <a class="nav-link" href="{{ route('activity.index') }}">
                        <i class="ni ni-single-copy-04 text-blue"></i> <b id="text">Tugasku</b>
                    </a>
                </li>
                <li class="nav-item" id="as">
                    <a class="nav-link" href="{{ route('activity.index') }}">
                        <i class="ni ni-books text-blue"></i> <b id="text">Penugasan</b>
                    </a>
                </li>
                    <li class="nav-item" id="asd">
                    <a class="nav-link" href="{{ route('activity.index') }}">
                        <i class="ni ni-collection text-blue"></i> <b id="text">Pelaporan</b>
                    </a>
                </li>
                
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
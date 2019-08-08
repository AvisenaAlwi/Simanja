@extends('layouts.app', ['class' => 'bg-default'])

@section('content')
<style>

/* Blue */

.info:hover {
  background: white;
  color: white;
}

.butstyle {
  border: 2px solid #abcbff;
  color: #abcbff;
  padding: 6px 48px;
  font-size: 16px;
  cursor: pointer;
}

</style>
    <div class="header bg-gradient-primary py-9 py-lg-9">
        <div class="container">
            <div class="header-body text-center mt-5 mb--4">
                <div class="row justify-content-center">
                    <div class="col-lg-6 col-md-6">
                        {{-- <h5>Dari <img src="{{ asset('img/FILKOM_logo.png') }}" class="img-fluid" alt="Responsive image" width="20%"> --}}
                            {{-- untuk <img src="{{ asset('img/BPS_logo.png') }}" class="img-fluid" alt="Responsive image" width="5%"> --}}
                            {{-- untuk Badan Pusat Statistik Kota Malang kami persembahkan</h5> --}}
                        <img src="{{ asset('img/logo simanja fix.png') }}" class="img-fluid" alt="Responsive image">
                        <h1>Sistem Informasi Manajemen Beban Kerja</h1>
                        <br>
                        @guest
                        <a class="nav-link nav-link-icon" href="{{ route('login') }}">
                            <button type="button" class="btn butstyle info bg-gradient-primary" style="border-radius: 25px;"> <i class="ni ni-key-25"></i> Login</button>
                        </a>
                        @endguest

                                {{-- <a class="nav-link nav-link-icon" href="{{ route('login') }}">
                                    <i class="ni ni-key-25"></i>
                                    <span class="nav-link-inner--text">Login</span>
                                </a>
                            </li> --}}

                        {{-- <h1 class="text-white">{{ 'Selamat datang di '.config('app.name') }}<br>[Sistem Manajemen Beban Kerja]</h1> --}}
                    </div>
                </div>
            </div>
        </div>
        <div class="separator separator-bottom separator-skew zindex-100">
            <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1" xmlns="http://www.w3.org/2000/svg">
                <polygon class="fill-default" points="2560 0 2560 100 0 100"></polygon>
            </svg>
        </div>
    </div>

    <div class="container mt--5 pb-5"></div>
@endsection

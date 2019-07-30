@extends('layouts.app', ['title' => __('User Profile')])

@section('content')
@include('users.partials.header', [
'title' => 'Hai' . ' '. auth()->user()->name,
'description' => 'Ini adalah laman data diri dan informasi khusus untuk anda, selamat bekerja :D'
])

<div class="container-fluid mt--7">
    <div class="row">
        <div class="col-xl-4 order-xl-2 mb-5 mb-xl-0">
            <div class="card card-profile shadow">
                <div class="row justify-content-center">
                    <div class="col-lg-3 order-lg-2">
                        <div class="card-profile-image">
                            <a href="#">
                                <img src="{{ asset('storage') }}/{{auth()->user()->photo}}"class="rounded-circle">
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-header text-center border-0 pt-8 pt-md-4 pb-0 pb-md-4">
                    <div class="d-flex justify-content-between">
                    </div>
                </div>
                <div class="card-body pt-0 pt-md-4">
                    <div class="row">
                        <div class="col">
                            <div class="text-center mt-md-5">
                                <h1>{{ auth()->user()->name }}</h1>
                            </div>
                            <div class="card-profile-stats d-flex justify-content-center mt-md--2">
                                <div>
                                    <span class="heading">{{ $jumlahTugasYangDiemban }}</span>
                                    <span class="description">Total Kegiatan Yang Diemban</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <a href="myactivity" title="Melihat tabel kegiatanku" data-toggle="tooltip" data-placement="top"><button
                                type="button" class="btn btn-primary ">Kegiatanku</button></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-8 order-xl-1">
            <div class="card bg-secondary shadow">
                <div class="card-header bg-white border-0">
                    <div class="row align-items-center">
                        <h3 class="col-12 mb-0">{{ __('Profilku') }}</h3>
                    </div>
                </div>
                <div class="card-body">

                    @if (session('password_status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('password_status') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif
                    <h3>Nama : {{auth()->user()->name}}</h3>
                    <h3>NIP : {{auth()->user()->nip}}</h3>
                    <h3>E-mail : {{auth()->user()->email}}</h3>
                    <h3>Menjabat sebagai {{auth()->user()->jabatan}}</h3>
                    <h5  class="text-muted mb-4">
                        {{ __('Ada kesalahan pada data diri atau lupa password? segera hubungi admin') }}</h5>
                    <div class="col text-right">
                        <a title="Mengganti password lama dengan password yang baru" data-toggle="tooltip" data-placement="top" href="{{ route('profile.edit') }}"><button type="button" class="btn btn-primary btn-sm">Ganti
                                password</button></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('layouts.footers.auth')
</div>
@endsection

@inject('Carbon', 'Carbon\Carbon')

@extends('layouts.app', ['title' => __('User Profile')])

@push('style')
<style>
    .zoom{
        position: fixed;
        left: 50%;
        top: 40%;
        transform: translate(-50%, -50%);
        transform: scale(2);
        z-index: 1000;
        background: rgba(255, 255, 255, .5);
        transition: all 1s;
    }
    #overlay{
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        position: fixed;
        background: rgba(255, 255, 255, .5);
        z-index: 999;
        display: none;
    }
</style>
@endpush
@section('content')
@include('users.partials.header', [
'title' => '',
// 'description' => 'Ini adalah laman data diri dan informasi khusus untuk anda, selamat bekerja :D'
])

<div class="container-fluid mt--7">
    <div class="row">
        <div class="col-xl-4 order-xl-2 mb-5 mb-xl-0">
            <div class="card card-profile shadow">
                <div class="row justify-content-center">
                    <div class="col-lg-3 order-lg-2">
                        <div id="overlay"></div>
                        <div class="card-profile-image" style="transition: all 1s;">
                            <img src="{{ asset('storage') }}/{{ $user->photo }}"class="rounded-circle">
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
                            <div class="card-profile-stats d-flex justify-content-center mt-md-5">
                                <div>
                                    <span class="heading">{{ $jumlahTugasYangDiemban }}</span>
                                    <span class="description">Total Kegiatan Yang Diemban</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-8 order-xl-1">
            <div class="card bg-secondary shadow">
                <div class="card-header bg-white border-0">
                    <div class="row align-items-center">
                        <h3 class="col-12 mb-0">Profil</h3>
                    </div>
                </div>
                <div class="card-body">
                    <h3>Nama : {{ $user->name }}</h3>
                    <h3>NIP : {{ $user->nip }}</h3>
                    <h3>E-mail : {{ $user->email }}</h3>
                    <h3>Menjabat sebagai {{ $user->jabatan }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3 mt-lg-5">
        <div class="col">
            <div class="card shadow">
                <div class="card-header border-0">
                    <h3 class="mb-0">Kegiatan yang diemban bulan ini</h3>
                </div>
                <div class="table-responsive">
                    <table class="table tablesorter align-items-center table-flush table-hover" id="tabel" style="min-height: 150px">
                        <thead class="thead-light">
                            <tr>
                                <th>Nama Kegiatan</th>
                                <th>Target Bulan Ini</th>
                            </tr>
                        </thead>
                        <tbody class="list">
                            @forelse ($assignments as $assignment)
                            @php
                                $now = $Carbon::now()->timezone('Asia/Jakarta')->formatLocalized('%B_%Y');
                                $targetVolume = json_decode($assignment->petugas, true)[$user->id][$now];
                            @endphp
                            <tr>
                                <th scope="row">
                                    <div class="media align-items-center">
                                        <a href="{{ route('activity.show', $assignment->id) }}">
                                            <div class="media-body">
                                                <span class="name mb-0 text-sm">
                                                    {{ $assignment->subActivity->name }} {{ $assignment->activity->name }}
                                                </span>
                                            </div>
                                        </a>
                                    </div>
                                </th>
                                <td>
                                    {{ $targetVolume }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">
                                    <h3>Tidak ada kegiatan</h3>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.footers.auth')
</div>
@endsection
@push('js')
    <script>
        $('#overlay').hide();
        $('.card-profile-image').click(function(){
            $(this).addClass('zoom');
            $('#overlay').fadeIn();
        });
        $('#overlay').click(function(){
            $('.card-profile-image').removeClass('zoom');
            $('#overlay').fadeOut();
        });

    </script>
@endpush

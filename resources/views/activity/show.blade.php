@inject('Carbon', '\Carbon\Carbon')
@inject('User', 'App\User')
@inject('Activity', '\App\Activity')
@extends('layouts.app', ['title' => $sub_activity->full_name])

@push('style')
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" type="text/css" href="{{ asset('vendor/bootstrapslider') }}/bootstrap-slider.min.css" />
<style>
    .slider.slider-horizontal {
        margin-left: 7%;
        margin-bottom: 10px !important;
        font-size: 13px;
    }

</style>
@endpush


@section('content')
@include('users.partials.header', [
'title' => 'Detail kegiatan',
// 'description' => 'halaman berikut menunjukkan Detail kegiatan yang yang telah dibuat.'
])

<div class="container-fluid mt--7">
    <div class="row">
        <div class="col-xl-12 order-xl-1">
            <div class="card bg-secondary shadow">
                <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="text-muted">Kegiatan</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('activity.index') }}"
                                    title="Kembali" data-toggle="tooltip" data-placement="top">
                                    <button type="button"
                                        class="btn btn-primary btn-sm"><span class="ni ni-bold-left"></span>
                                    </button>
                                </a>
                            </div>
                        </div>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h1 class="display-4 text-success">{{$sub_activity->activity_name}}</h1>
                        </div>
                        <div class="col-6 text-right">
                            <h5>Dibuat :
                                {{ $Carbon::createFromTimeStamp(strtotime($sub_activity->created_at))->diffForHumans() }}
                            </h5>
                        </div>
                    </div>
                        <h4>dibuat oleh: {{$sub_activity->user_name}}</h4>
                        @if (date_create($sub_activity->awal)->format("Y-m") == date_create($sub_activity->akhir)->format("Y-m"))
                        <h4>Periode: {{ $Carbon::parse($sub_activity->awal)->formatLocalized('%B %Y') }}
                        @else
                        <h4>Periode: {{ $Carbon::parse($sub_activity->awal)->formatLocalized('%B %Y') . ' - ' .
                            $Carbon::parse($sub_activity->akhir)->formatLocalized('%B %Y')}}</h4>
                        @endif
                        <h4>Kategori: {{$sub_activity->kategori}}
                </div>
                <div class="card-header bg-white border-0">
                    <div class="row">
                        <div class="col-md-12">
                            <h3 class="text-muted">Sub kegiatan</h3>
                        </div>
                    </div>
                </div>
                    <div class="card-body">
                            <h1 class="display-4 text-success">{{$sub_activity->sub_activity_name}}</h1>
                            <h4>Satuan: {{$sub_activity->satuan}} | Volume: {{$sub_activity->volume}} | Kode Butir Kegiatan : {{ $sub_activity->kode_butir != null ? $sub_activity->kode_butir : ' -' }}
                                | Angka Kredit : {{$sub_activity->angka_kredit != null ? $sub_activity->kode_butir : ' -'}}</h4>
                            <h4>Pendidikan minimal: {{$sub_activity->pendidikan}}</h4>
                            <h4>Kualifikasi minimal:</h4>
                            <table class="table table-sm table-dark">
                                <thead>
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Keahlian</th>
                                        <th scope="col">Tingkat</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row">1</th>
                                        <td>TI</td>
                                        <td>{{$sub_activity->ti}}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">2</th>
                                        <td>Menulis</td>
                                        <td>{{$sub_activity->menulis}}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">3</th>
                                        <td>Administrasi</td>
                                        <td>{{$sub_activity->administrasi}}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">4</th>
                                        <td>Pengalaman survei</td>
                                        <td>{{$sub_activity->pengalaman_survei}}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <br>
                            <h4>Petugas pengemban :</h4>
                            @php
                            $result = json_decode($sub_activity->petugas,true);
                            $users_size = sizeof($result);
                            $counter = 0;
                            @endphp
                            @if ($users_size!=0)
                            <div class="table-responsive">
                                <table class="table table-sm table-dark">
                                    <thead>
                                        <tr>
                                            <th scope="col">No</th>
                                            <th scope="col">NIP</th>
                                            <th scope="col">Nama</th>
                                            <th scope="col">Jabatan</th>
                                            <th scope="col">E-mail</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($result as $petugas => $value)
                                        @php $counter = $counter + 1;
                                        $users_data = $User::find($petugas);
                                        @endphp
                                        <tr>
                                            <th scope="row">{{$counter}}</th>
                                            <td>{{$users_data->nip}}</td>
                                            <td>{{$users_data->name}}</td>
                                            <td>{{$users_data->jabatan}}</td>
                                            <td>{{$users_data->email}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                                <h4 class="text-center">Belum ada pegawai yang menerima tugas kegiatan ini</h4>
                                @if (auth()->user()->role_id == 1 || $Activity::find($sub_activity->activity_id)->created_by_user_id == auth()->user()->id)
                                <div class="text-center">
                                    <a href="{{ route('assignment.edit', $sub_activity->sub_activity_id) }}" class="btn btn-warning">
                                        <i class="ni ni-single-copy-04"></i> Tugaskan
                                    </a>
                                </div>
                                @endif
                            @endif
                        </div>
            </div>
        </div>
    </div>
    @include('layouts.footers.auth')
</div>



@endsection

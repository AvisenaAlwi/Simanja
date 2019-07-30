@inject('Carbon', '\Carbon\Carbon')
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

    #id {}

    th,
    td {
        text-align: center;
    }

</style>
@endpush


@section('content')
@include('users.partials.header', [
'title' => 'Detail Pelaporan Kegiatan',
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
                            <a href="{{ route('assignment.index') }}"
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
                                    <h1 class="display-4">{{$sub_activity->activity_name}}</h1>
                                </div>
                                <div class="col-6 text-right">
                                    <h5>Dibuat :
                                        {{ $Carbon::createFromTimeStamp(strtotime($sub_activity->created_at))->diffForHumans() }}
                                    </h5>
                                </div>
                            </div>
                            <h4>dibuat oleh: {{$sub_activity->user_name}}</h4>
                            @if (date_create($sub_activity->awal)->format("Y-m") ==
                            date_create($sub_activity->akhir)->format("Y-m"))
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
                            <h1 class="display-4">{{$sub_activity->sub_activity_name}}</h1>

                            @if ($sub_activity->created_by_user_id == Auth()->user()->id)
                            <h4>Petugas pengemban:</h4>
                            @php
                            $result = json_decode($sub_activity->petugas,true);
                            $users_size = sizeof($result);
                            $counter = 0;
                            @endphp
                        </div>
                        @if ($users_size!=0)
                        <div class="table-responsive">
                            <form action="" method="get">
                                <table class="table table-sm table-dark">
                                    <thead>
                                        <tr>
                                            <th id='no' scope="col">No</th>
                                            <th scope="col">NIP</th>
                                            <th scope="col">Nama</th>
                                            <th scope="col">Jabatan</th>
                                            <th scope="col">E-mail</th>
                                            <th scope="col">Realisasi</th>
                                            <th scope="col">Keterangan</th>
                                            @if ($sub_activity->created_by_user_id == Auth()->user()->id)
                                            <th scope="col">Tingkat kualitas (%)</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $users_data)
                                        @foreach ($result as $petugas)
                                        @if ($petugas==$users_data->id)
                                        @php $counter = $counter + 1;
                                        @endphp
                                        <tr>
                                            <th scope="row">{{$counter}}</th>
                                            <td>{{$users_data->nip}}</td>
                                            <td>{{$users_data->name}}</td>
                                            <td>{{$users_data->jabatan}}</td>
                                            <td>{{$users_data->email}}</td>
                                            <td><input type="number" name="realisasi_{{$users_data->id}}" id=""
                                                    style="width: 50px"></td>
                                            <td><textarea name="keterangan_{{$users_data->id}}" id="" cols="30"
                                                    rows="1"></textarea></td>
                                            <td><input type="number" name="kualitas_{{$users_data->id}}" id=""
                                                    style="width: 50px"></td>
                                        </tr>
                                        @endif
                                        @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                        </div>
                        <br>
                        </form>
                        @else
                        <h4 class="text-center">Belum ada pegawai yang menerima tugas kegiatan ini</h4>
                        <div class="col-7 text-right">
                            <a href="{{ route('assignment.edit', $sub_activity->sub_activity_id) }}"
                                class="btn btn-warning">
                                <i class="ni ni-single-copy-04"></i> Tugaskan</a>
                        </div>

                        @endif
                        @else
                        <div class="col-lg-2">
                            <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                <label class="form-control-label" for="input-realization">Realisasi</label>
                                <input type="number" name="realization" id="input-realization"
                                    class="form-control form-control-alternative{{ $errors->has('realization') ? ' is-invalid' : '' }}"
                                    value="{{ old('realization') }}" required autofocus>

                                @if ($errors->has('realization'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('realization') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                                <label class="form-control-label">Keterangan</label>
                                <textarea name="Keterangan" id="" rows="2" class="form-control form-control-alternative Keterangan"></textarea>
                            </div>
                        @endif
                    </div>
                    <div class="col-7 text-right">
                        <a href="" class="btn btn-success">Simpan</a>
                    </div>
                    <h1 class="display-4">{{$sub_activity->activity_name}}</h1>
                    <h4>dibuat oleh: {{ $sub_activity->user_name }}</h4>
                    @if (date_create($sub_activity->awal)->format("Y-m") == date_create($sub_activity->akhir)->format("Y-m"))
                    <h4>Periode: {{ $Carbon::parse($sub_activity->awal)->formatLocalized('%B %Y') }}</h4>
                    @else
                    <h4>Periode: {{ $Carbon::parse($sub_activity->awal)->formatLocalized('%B %Y') . ' - ' .
                    $Carbon::parse($sub_activity->akhir)->formatLocalized('%B %Y')}}</h4>
                    @endif
                    <h4>Kategori: {{$sub_activity->kategori}}</h4>
                    <hr>
                    <div>
                        <h6 class="heading-small text-muted mb-4">Sub Kegiatan</h6>
                        <h1 class="display-4">{{$sub_activity->sub_activity_name}}</h1>
                        <h4>Petugas pengemban:</h4>
                    </div>
                    @php
                    $result = json_decode($sub_activity->petugas,true);
                    $users_size = sizeof($result);
                    $counter = 0;
                    @endphp
                    @if ($users_size!=0)
                    <form action="" method="get">
                        <div class="table-responsive">
                            <table class="table table-sm table-dark">
                                <thead>
                                    <tr>
                                        <th id='no' scope="col">No</th>
                                        <th scope="col">NIP</th>
                                        <th scope="col">Nama</th>
                                        <th scope="col">Jabatan</th>
                                        <th scope="col">E-mail</th>
                                        <th scope="col">Realisasi</th>
                                        <th scope="col">Keterangan</th>
                                        <th scope="col">Tingkat kualitas (%)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $users_data)
                                    @foreach ($result as $petugas)
                                    @if ($petugas==$users_data->id)
                                    @php $counter = $counter + 1;
                                    @endphp
                                    <tr>
                                        <th scope="row">{{$counter}}</th>
                                        <td>{{$users_data->nip}}</td>
                                        <td>{{$users_data->name}}</td>
                                        <td>{{$users_data->jabatan}}</td>
                                        <td>{{$users_data->email}}</td>
                                        <td><input type="number" name="realisasi_{{$users_data->id}}" id=""
                                                style="width: 50px"></td>
                                        <td><textarea name="keterangan_{{$users_data->id}}" id="" cols="30"
                                                rows="1"></textarea></td>
                                        <td><input type="number" name="kualitas_{{$users_data->id}}" id=""
                                                style="width: 50px"></td>
                                    </tr>
                                    @endif
                                    @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <br>
                        <input class="btn btn-success center" type="submit" value="Simpan">
                    </form>
                    @else
                    <h4 class="text-center">Belum ada pegawai yang menerima tugas kegiatan ini</h4>
                    <a href="{{ route('assignment.edit', $sub_activity->sub_activity_id) }}">
                        <button class="mx-auto w-auto p-3 btn btn-warning btn-block text-center">
                            <i class="ni ni-single-copy-04"></i>Tugaskan
                        </button>
                    </a>
                    @endif
                </div>
            </div>
            @include('layouts.footers.auth')
        </div>
    </div>
    @include('layouts.footers.auth')
</div>
@endsection

@inject('Input', 'Illuminate\Support\Facades\Input')
@inject('Carbon', '\Carbon\Carbon')
@inject('User', '\App\User')
@extends('layouts.app', ['title' => 'Pelaporan '.$sub_activity->full_name])

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
@php
$months = config('scale.month');
$currentMonth = $Carbon::now()->formatLocalized('%B');
$currentYear = $Carbon::now()->format('Y');
$monthQuery = $Input::get('month','now');
$yearQuery = $Input::get('year',$currentYear);
@endphp

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
                            <a href="{{ redirect()->getUrlGenerator()->previous() }}"
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
                        <div class="row align-items-center">
                            <div class="col-12">
                                <h3 class="text-muted">Sub Kegiatan</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-12">
                                    <h1 class="display-4 text-success">{{$sub_activity->sub_activity_name}}</h1>
                                </div>
                            </div>
                            {{-- @foreach ($collection as $item) --}}

                            {{-- @endforeach --}}
                            <h4></h4>
                            @php
                            // dd($sub_activity->petugas);
                            $petugasArray = json_decode($sub_activity->petugas,true);
                            $realisasiArray = json_decode($sub_activity->realisasi,true);
                            $tingkulArray = json_decode($sub_activity->tingkat_kualitas,true);
                            $keteranganArray = json_decode($sub_activity->keterangan_r,true);
                            $users_size = sizeof($petugasArray);
                            $user_data_save = array_keys($petugasArray);
                            @endphp

                     </div>
                        @if ($users_size!=0)
                        {{-- @php dd($period); @endphp --}}
                        <div class="accordion" id="accordionExample">
                            @foreach ($period as $periode)
                            @php
                            $counter = 0;
                            $idCollapse = str_replace(" ","_",$periode['monthName']);
                            @endphp
                            <div class="card">
                                <div class="card-header" id="_{{ $idCollapse }}">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link" type="button" data-toggle="collapse"
                                            data-target="#{{ $idCollapse }}" aria-expanded="true"
                                            aria-controls="{{ $idCollapse }}">
                                            {{ $periode['monthName'] }}
                                        </button>
                                    </h2>
                                </div>
                                @if (($currentMonth = $Carbon::now()->formatLocalized("%B %Y")) ==
                                $periode['monthName'])
                                <div id="{{ $idCollapse }}" class="collapse show" aria-labelledby="_{{ $idCollapse }}"
                                    data-parent="#accordionExample">
                                    @else
                                    <div id="{{ $idCollapse }}" class="collapse" aria-labelledby="_{{ $idCollapse }}"
                                        data-parent="#accordionExample">
                                        @endif
                                        <div class="card-body">
                                            <h4>Periode : {{ $periode['monthName'] }}</h4>

                                            @if ($sub_activity->created_by_user_id == Auth()->user()->id)
                                            <h4>Petugas pengemban:</h4>
                                            <div class="table-responsive">
                                                <table class="table table-sm table-dark">
                                                    <thead>
                                                        <tr>
                                                            <th id='no' scope="col">No</th>
                                                            <th scope="col">NIP</th>
                                                            <th scope="col">Nama</th>
                                                            <th scope="col">Realisasi</th>
                                                            <th scope="col">Keterangan</th>
                                                            <th scope="col">Tingkat kualitas (%)</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($petugasArray as $idPetugas => $months)
                                                        @php $counter = $counter + 1;
                                                        $user_data = $User::find($idPetugas);
                                                        @endphp
                                                        <tr>
                                                            <th scope="row">{{$counter}}</th>
                                                            <td>{{$user_data->nip}}</td>
                                                            <td>{{$user_data->name}}</td>
                                                            <td>
                                                                <input type="number" name="realisasi_{{$user_data->id}}" class="realisasi"
                                                                    value="{{ (int)json_decode($sub_activity->realisasi)->$idPetugas->$idCollapse }}"
                                                                    style="width: 50px" placeholder="Belum diisi"
                                                                    data-id="{{ $idCollapse }}_{{ $user_data->id }}"
                                                                    max="{{ $petugasArray[$user_data->id][$idCollapse] }}" min="0">
                                                                </td>
                                                            <td><textarea class="keterangan"
                                                                    id="keterangan_{{$user_data->id}}" cols="30"
                                                                    rows="1" placeholder="Belum diisi"
                                                                    data-id="{{ $idCollapse }}_{{ $user_data->id }}">{{ json_decode($sub_activity->keterangan_r)->$idPetugas->$idCollapse }}</textarea>
                                                            </td>
                                                            <td><input type="number" name="kualitas_{{$user_data->id}}" class="kualitas"
                                                                    value="{{ json_decode($sub_activity->tingkat_kualitas)->$idPetugas->$idCollapse }}"
                                                                    id="kualitas_{{$user_data->id}}"
                                                                    style="width: 50px" placeholder="Belum diisi"
                                                                    data-id="{{ $idCollapse }}_{{ $user_data->id }}"
                                                                    max="100" min="0">
                                                                </td>
                                                        </tr>
                                                        @endforeach
                                                        @php
                                                        //  dd($user_data_save);
                                                        @endphp
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="row">
                                                <div class="col-12 d-flex justify-content-center">
                                                    <button class="mx-auto m-3 w-auto btn btn-success btn-save-realisasi text-center" data-title="{{ $sub_activity->full_name.' '.$periode['monthName'] }}"
                                                        month-year="{{ $idCollapse }}"> Simpan
                                                    </button>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <h4 class="text-center">Belum ada pegawai yang menerima tugas kegiatan ini</h4>
                            <div class="col-12 text-center mb-4 mt-1">
                                <a href="{{ route('assignment.edit', $sub_activity->sub_activity_id) }}"
                                    class="btn btn-warning">
                                    <i class="ni ni-single-copy-04"></i> Tugaskan</a>
                            </div>
                            @endif
                        </div>
                </div>
            </div>
            @include('layouts.footers.auth')
        </div>
    </div>
    @endsection
    @push('js')
    <script src="{{ asset('vendor/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('vendor/axios/axios.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('.btn-save-realisasi').click(function (e) {
                e.preventDefault();
                let me = $(this);
                let title = me.attr('data-title');
                let id = "{{ $idz }}";
                let monthYear = me.attr('month-year');
                Swal.fire({
                    title: 'Simpan pelaporan',
                    html: 'Yakin ingin menyimpan <h3>' + title + ' ?</h3>',
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, simpan',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.value) {
                        let realisasi = [];
                        let keterangan = [];
                        let kualitas = [];
                        var userArray = {{ json_encode($user_data_save) }};
                        for (let index = 0; index < userArray.length; index++)
                        {
                            console.log(monthYear);
                            realisasi.push(parseInt($('.realisasi[data-id="'+monthYear+'_'+userArray[index]+'"]').val()));
                            keterangan.push($('.keterangan[data-id="'+monthYear+'_'+userArray[index]+'"]').val());
                            kualitas.push($('.kualitas[data-id="'+monthYear+'_'+userArray[index]+'"]').val());
                        }
                        axios({
                            method: 'put',
                            url: '{{ url('/') }}/report/update/' + id,
                            data: {
                                userArray: JSON.stringify(userArray),
                                monthYear: monthYear,
                                realisasi: JSON.stringify(realisasi),
                                kualitas: JSON.stringify(kualitas),
                                keterangan: JSON.stringify(keterangan)
                            }
                        }).then(function (res) {
                            Swal.fire({
                                title: 'Berhasil',
                                html: "<b>" + title + "</b> berhasil disimpan",
                                type: 'success'
                            });
                        }).catch(function (err) {
                            console.error(err);
                            Swal.fire('Gagal Menyimpan',
                                "Terjadi kesalahan saat menyimpan", 'error');
                        });
                    }
                });
            });
        });
    </script>
    @endpush

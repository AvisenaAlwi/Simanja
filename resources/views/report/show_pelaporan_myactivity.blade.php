@inject('Input', 'Illuminate\Support\Facades\Input')
@inject('Carbon', '\Carbon\Carbon')
@inject('User', '\App\User')
@extends('layouts.app', ['title' => 'Pelaporan '])

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
                            <h3 class="text-muted">KegiatanKu</h3>
                        </div>
                        <div class="col-4 text-right">
                            <a href="{{ redirect()->getUrlGenerator()->previous() }}" title="Kembali"
                                data-toggle="tooltip" data-placement="top">
                                <button type="button" class="btn btn-primary btn-sm"><span
                                        class="ni ni-bold-left"></span>
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h1 class="display-4 text-success">{{ $myActivity->name}}</h1>
                        </div>
                        <div class="col-6 text-right">
                            <h5>Dibuat :
                                {{ $Carbon::createFromTimeStamp(strtotime($myActivity->created_at))->diffForHumans() }}
                            </h5>
                        </div>
                    </div>
                    <h4>dibuat oleh: {{$myActivity->owner->name}}</h4>
                    {{-- @if (date_create($myActivity->awal)->format("Y-m") ==
                    date_create($myActivity->akhir)->format("Y-m"))
                    <h4>Periode: {{ $Carbon::parse($myActivity->awal)->formatLocalized('%B %Y') }}
                    @else
                    <h4>Periode: {{ $Carbon::parse($myActivity->awal)->formatLocalized('%B %Y') . ' - ' .
                                $Carbon::parse($myActivity->akhir)->formatLocalized('%B %Y')}}</h4>
                    @endif --}}
                    <h4>Kategori: {{$myActivity->kategori}}
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
                            <h1 class="display-4 text-success">{{$myActivity->name}}</h1>
                        </div>
                    </div>
                    <h4></h4>

                </div>
                <div class="card">
                    <div class="card-body">
                        <h4>Petugas pengemban:</h4>
                        <div class="table-responsive">
                            <table class="table table-sm table-dark">
                                <thead>
                                    <tr>
                                        <th scope="col">NIP</th>
                                        <th scope="col">Nama</th>
                                        <th scope="col">Volume</th>
                                        <th scope="col">Realisasi</th>
                                        <th scope="col">Keterangan</th>
                                        <th scope="col">Tingkat kualitas (%)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $myActivity->owner->nip }}</td>
                                        <td>{{ $myActivity->owner->name }}</td>
                                        <td>{{ $myActivity->volume }}</td>
                                        <td>
                                            <input type="number" class="realisasi" value="{{ $myActivity->realisasi }}"
                                                style="width: 50px" placeholder="Belum diisi"
                                                max="{{ $myActivity->volume }}" min="0" id="realisasi">
                                        </td>
                                        <td><textarea class="keterangan" id="keterangan" cols="30" rows="1"
                                                placeholder="Belum diisi">{{ $myActivity->keterangan_r }}</textarea>
                                        </td>
                                        <td><input type="number" class="kualitas" id="kualitas"
                                                value="{{ $myActivity->tingkat_kualitas }}" style="width: 50px"
                                                placeholder="Belum diisi" max="100" min="0">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-12 d-flex justify-content-center">
                                <button class="mx-auto m-3 w-auto btn btn-success btn-save-realisasi text-center"
                                    id="simpan"> Simpan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
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
        $('#simpan').click(function (e) {
            e.preventDefault();
            let me = $(this);
            let id = {{ $myActivity->id }};
            Swal.fire({
                title: 'Simpan pelaporan',
                html: 'Yakin ingin menyimpan?',
                type: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, simpan',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.value) {
                    let realisasi = $('#realisasi').val();
                    let keterangan = $('#keterangan').val();
                    let kualitas = $('#kualitas').val();
                    axios({
                        method: 'put',
                        url: '{{ url('/') }}/report/update-my-activity/' + id,
                        data: {
                            realisasi: realisasi,
                            tingkat_kualitas: kualitas,
                            keterangan: keterangan
                        }
                    }).then(function (res) {
                        Swal.fire({
                            title: 'Berhasil',
                            html: "Berhasil disimpan",
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

        $('#realisasi, #kualitas').on('input', function () {
            var value = $(this).val();
            let maxValue = $(this).attr('max');
            if ((value !== '') && (value.indexOf('.') === -1)) {
                $(this).val(Math.max(Math.min(value, maxValue), 0));
            }
        });
    });

</script>
@endpush

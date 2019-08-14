@inject('Input', 'Illuminate\Support\Facades\Input')
@inject('Carbon', '\Carbon\Carbon')
@inject('Activity', '\App\Activity')

@extends('layouts.app', ['showSearch' => true, 'title' => 'KegiatanKu'])
@push('style')
<style>
input.realisasi,
input.keterangan,
input.tingkat-kualitas,
input.realisasi-myactivity,
input.keterangan-myactivity,
input.tingkat-kualitas-myactivity{
    border: none;
    background: transparent;
    border-bottom: 1px solid #888;
    width: 100px;
    border-radius: 0;
    outline: none;
}
.percent{
    content: "%";
}
</style>
@endpush
@php
$months = config('scale.month');
$currentMonth = $Carbon::now()->formatLocalized('%B');
$currentYear = $Carbon::now()->format('Y');
$monthQuery = $Input::get('month','now');
$yearQuery = $Input::get('year',$currentYear);
if($monthQuery == 'now')
    $monthQuery = $currentMonth;
@endphp
@section('content')
@include('users.partials.header', [
'title' => 'Kegiatanku',
'description' => 'Kegiatan yang Anda emban'
])

<div class="container-fluid mt--7">
    <div class="row">
        <div class="col">
            <div class="card shadow">
                <div class="card-header border-0">
                    <form id="formChange" action="{{ route('myactivity.index') }}" method="get">
                        <div class="row align-items-center">
                            <div class="col-12 col-lg-4 my-1 my-lg-0">
                                <h3 class="mb-0">
                                    @if ($monthQuery == 'now')
                                    Kegiatan bulan ini
                                    @else
                                    Kegiatan bulan {{ $monthQuery }} {{ $yearQuery }}
                                    @endif
                                </h3>
                            </div>
                            <div class="col-6 col-lg-2 my-1 my-lg-0">
                                <select name="month" id="select" class="browser-default custom-select">
                                    @foreach($months as $m)
                                        @if (($currentMonth = $Carbon::now()->formatLocalized("%B")) == $m)
                                        <option value="now" {{ $monthQuery==$currentMonth || $monthQuery=='now' ? 'selected' : '' }}>Bulan sekarang</option>
                                        @else
                                        <option value="{{ $m }}" {{ $monthQuery==$m ? 'selected' : '' }}>{{ $m }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6 col-lg-2 my-1 my-lg-0">
                                <select name="year" id="select" class="browser-default custom-select">
                                    @php $x = 2019; @endphp
                                    @while ($x <= $currentYear)
                                        <option value="{{ $x }}" {{ $x == $yearQuery ? 'selected' : '' }}>{{ $x }}</option>
                                        @php
                                        $x += 1;
                                        @endphp
                                    @endwhile
                                </select>
                            </div>
                            <div class="col-12 col-lg-4 text-center my-1 my-lg-0">
                                <a href="{{ route('report.print_ckpt', ['month' => $monthQuery, 'year' => $yearQuery, 'ckp' => 't']) }}" target="_blank" title="Cetak CKP-T Bulan {{ $monthQuery }} {{ $yearQuery }}" data-toggle="tooltip" data-placement="top" class="mr-3">
                                    <button type="button" class="btn btn-warning btn-sm">
                                        <i class="fa fa-print"></i> Cetak CKP-T
                                    </button>
                                </a>
                                <a href="{{ route('report.print_ckpr', ['month' => $monthQuery, 'year' => $yearQuery, 'ckp' => 'r']) }}" target="_blank" title="Cetak CKP-R Bulan {{ $monthQuery }} {{ $yearQuery }}" title="Cetak CKP-R Bulan {{ $monthQuery }} {{ $yearQuery }}" data-toggle="tooltip" data-placement="top">
                                    <button type="button" class="btn btn-success btn-sm">
                                        <i class="fa fa-print"></i> Cetak CKP-R
                                    </button>
                                </a>
                                <a href="{{ route('myactivity.create') }}"
                                    class="ml-3"
                                    title="Tambah kegiatan sendiri diluar yang ditetapkan supervisor untuk bulan sekarang" data-toggle="tooltip" data-placement="left">
                                    <button type="button" class="btn btn-primary btn-sm">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="table tablesorter align-items-center table-flush table-hover" id="tabel" style="min-height: 150px">
                        <thead class="thead-light">
                            <tr>
                                <th>Nama Kegiatan</th>
                                <th>Waktu</th>
                                <th>Diberikan Oleh</th>
                                <th>Volume</th>
                                <th>Realisasi</th>
                                <th>Tingkat kualitas</th>
                                <th>Keterangan*</th>
                                <th></th>
                            </tr>
                        </thead>
                        <div class="col-12">
                            @if (session('status'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('status') }}
                                    {{-- <a href="{{ route('assignment.index')}}"> Tugaskan sekarang?</a> --}}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif
                        </div>
                        <tbody class="list">
                            @forelse ($sub_activity as $sub)
                            @php
                                $ket = json_decode($sub->keterangan_r, true)[auth()->user()->id]["${monthQuery}_${yearQuery}"];
                                $relal = json_decode($sub->realisasi, true)[auth()->user()->id]["${monthQuery}_${yearQuery}"];
                                $tingkul = json_decode($sub->tingkat_kualitas, true)[auth()->user()->id]["${monthQuery}_${yearQuery}"];
                                $maxRealisasi = json_decode($sub->petugas, true)[auth()->user()->id]["${monthQuery}_${yearQuery}"];
                            @endphp
                            <tr>
                                <th scope="row">
                                    <div class="media align-items-center">
                                        <a href="{{ route('activity.show', $sub->id) }}">
                                            <div class="media-body">
                                                <span class="name mb-0 text-sm">
                                                    {{ $sub->full_name }}
                                                </span>
                                            </div>
                                    </div>
                                    </a>
                                </th>
                                <td>
                                    @if ($Carbon::parse($sub->awal)->format('Y-m') == $Carbon::parse($sub->akhir)->format('Y-m') || $sub->akhir == null)
                                    {{ $Carbon::parse($sub->awal)->formatLocalized('%b %Y') }}
                                    @else
                                    {{ $Carbon::parse($sub->awal)->formatLocalized('%b %Y') . ' - ' . $Carbon::parse($sub->akhir)->formatLocalized('%b %Y') }}
                                    @endif
                                </td>
                                <td>
                                    <div class="avatar-group"><a href="#" data-toggle="tooltip">
                                            <a href="{{ route('employee', preg_replace('/\s+/', '', $sub->nip)) }}" data-toggle="tooltip"
                                                data-original-title="{{ $sub->users_name }}"
                                                class="avatar avatar-sm rounded-circle">
                                                <img alt="Image placeholder" src="{{ asset('storage') }}/{{ $sub->photo }}">
                                            </a>
                                    </div>
                                </td>
                                <td>
                                    {{ $maxRealisasi }}
                                </td>
                                <td>
                                    <input type="number" maxlength="50" placeholder="Belum diisi" value="{{ $relal }}" class="form-control realisasi" data-id="{{ $sub->id }}" min="0" max="{{ $maxRealisasi }}">
                                </td>
                                <td class="percent">
                                    <input type="number" maxlength="50" placeholder="Belum dinilai" value="{{ $tingkul }}" class="form-control realisasi" data-id="{{ $sub->id }}" min="0" disabled aria-disabled="true"
                                    title="Tidak bisa diisi karena yang berhak menilai adalah pembuat/pemberi kegiatan" data-toggle="tooltip" data-placement="top">
                                </td>
                                <td>
                                    <input type="text" maxlength="100" placeholder="Belum diisi" value="{{ $ket }}" class="form-control keterangan" data-id="{{ $sub->id }}" style="width: 150px !important">
                                </td>
                                <td class="text-right">
                                    <button class="btn btn-success btn-block btn-save-realisasi" data-id="{{ $sub->id }}" data-title="{{ $sub->full_name }}" month-year="{{ $monthQuery }}_{{ $yearQuery }}">
                                        <i class="fas fa-save"></i> Simpan
                                    </button>
                                    {{-- <li aria-haspopup="true" class="dropdown dropdown dropdown"><a role="button"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                            class="btn btn-sm btn-icon-only text-primary"><i
                                                class="fas fa-ellipsis-v" title="Aksi" data-toggle="tooltip" data-placement="left"></i></a>
                                        <ul class="dropdown-menu dropdown-menu-right">
                                            <a href="{{ route('activity.show', $sub->id) }}"
                                                class="dropdown-item"><i class="fa fa-info text-info"></i>Detail kegiatan</a>
                                                @if (auth()->user()->role_id == 1 || $Activity::find($sub->activity_id)->create_by_user_id == auth()->user()->id)
                                            <a href="{{ route('activity.edit', $sub->id) }}"
                                                class="dropdown-item"><i class="fa fa-edit text-success"></i>Edit</a>
                                            <a href="" class="dropdown-item btn-delete-item" title="{{ $sub->full_name }}"
                                                id-item="{{ $sub->id }}"><i class="fa fa-trash text-danger"></i> Hapus</a>
                                            @endif
                                        </ul>
                                    </li> --}}
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">
                                        <h3>Tidak ada kegiatan</h3>
                                    </td>
                                </tr>
                            @endforelse
                            <thead class="thead-light">
                                <tr>
                                    <th colspan="8">Kegiatan yang saya tambahkan</th>
                                </tr>
                            </thead>
                            @forelse ($my_activity as $activity)
                            @php
                                $maxRealisasi = $activity->volume;
                            @endphp
                            <tr>
                                <th scope="row">
                                    <div class="media align-items-center">
                                        <a href="{{ route('myactivity.show', $activity->id) }}">
                                            <div class="media-body">
                                                <span class="name mb-0 text-sm">
                                                    {{ $activity->name }}
                                                </span>
                                            </div>
                                    </div>
                                    </a>
                                </th>
                                <td>
                                    @if ($Carbon::parse($activity->awal)->format('Y-m') == $Carbon::parse($activity->akhir)->format('Y-m') || $activity->akhir == null)
                                    {{ $Carbon::parse($activity->awal)->formatLocalized('%b %Y') }}
                                    @else
                                    {{ $Carbon::parse($activity->awal)->formatLocalized('%b %Y') . ' - ' . $Carbon::parse($activity->akhir)->formatLocalized('%b %Y') }}
                                    @endif
                                </td>
                                <td>
                                    <div class="avatar-group"><a href="#" data-toggle="tooltip">
                                            <a href="{{ route('profile.index') }}" data-toggle="tooltip"
                                                data-original-title="{{ auth()->user()->name }}"
                                                class="avatar avatar-sm rounded-circle">
                                                <img alt="Image placeholder" src="{{ asset('storage') }}/{{auth()->user()->photo}}">
                                            </a>
                                    </div>
                                </td>
                                <td>{{ $maxRealisasi }}</td>
                                <td>
                                    <input type="number" maxlength="50" placeholder="Belum diisi" value="{{ $activity->realisasi }}" class="form-control realisasi-myactivity" data-id="{{ $activity->id }}" min="0" max="{{ $maxRealisasi }}">
                                </td>
                                <td>
                                    <input type="number" maxlength="50" placeholder="Belum dinilai" value="{{ $activity->tingkat_kualitas ?? 0 }}" class="form-control tingkat-kualitas-myactivity" data-id="{{ $activity->id }}" min="0">
                                </td>
                                <td>
                                    <input type="text" maxlength="100" placeholder="Belum diisi" value="{{ $activity->keterangan_r }}" class="form-control keterangan-myactivity" data-id="{{ $activity->id }}" style="width: 150px !important">
                                </td>
                                <td class="text-right">
                                    <button class="btn btn-success btn-save-realisasi-my-activity" data-id="{{ $activity->id }}" data-title="{{ $activity->name }}">
                                        <i class="fas fa-save"></i>
                                        Simpan
                                    </button>
                                    <li aria-haspopup="true" class="dropdown dropdown dropdown"><a role="button"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                            class="btn btn-sm btn-icon-only text-primary"><i
                                                class="fas fa-ellipsis-v" title="Aksi" data-toggle="tooltip" data-placement="left"></i></a>
                                        <ul class="dropdown-menu dropdown-menu-right">
                                            <a href="{{ route('myactivity.show', $activity->id) }}"
                                                class="dropdown-item"><i class="fa fa-info text-info"></i>Detail kegiatan</a>
                                            <a href="{{ route('myactivity.edit', $activity->id) }}"
                                                class="dropdown-item"><i class="fa fa-edit text-success"></i>Edit</a>
                                            <a href="" class="dropdown-item btn-delete-item-my-activity" title="{{ $activity->name }}"
                                                id-item="{{ $activity->id }}"><i class="fa fa-trash text-danger"></i> Hapus</a>
                                        </ul>
                                    </li>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">
                                    <h3>Tidak ada kegiatan</h3>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-12 col-lg-4 d-flex justify-content-center justify-content-lg-start align-items-center">
                            <h4>Total : {{ $sub_activity->count() + $my_activity->count() }} kegiatan</h4>
                        </div>
                    </div>
                    * = Optional, tidak harus diisi
                </div>
            </div>
        </div>
    </div>

    @include('layouts.footers.auth')
</div>
@endsection
@push('js')
<script src="{{ asset('vendor/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ asset('vendor/axios/axios.min.js') }}"></script>
<script src="{{ asset('vendor/bootstrapnotify/bootstrap-notify.min.js') }}"></script>
<script>
    $(document).ready(function () {
        $('.btn-save-realisasi').click(function (e) {
            e.preventDefault();
            let me = $(this);
            let title = me.attr('data-title');
            let id = me.attr('data-id');
            let monthYear = me.attr('month-year');
            Swal.fire({
                title: 'Simpan realisasi',
                html: 'Yakin ingin menyimpan <h3>' + title + ' ?</h3>',
                type: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, simpan',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.value) {
                    let realisasi = $('.realisasi[data-id='+id+']').val();
                    let keterangan = $('.keterangan[data-id='+id+']').val();
                    axios({
                        method: 'put',
                        url: '{{ url('/') }}/myactivity/update/' + id,
                        data: {user_id: {{ auth()->user()->id }}, month_year: monthYear, realisasi: realisasi, keterangan: keterangan}
                    }).then(function (res) {
                        Swal.fire({
                            title: 'Berhasil',
                            html: "<b>" + title + "</b> berhasil disimpan",
                            type: 'success'
                        });
                    }).catch(function (err) {
                        console.error(err);
                        Swal.fire('Gagal Menyimpan', "Terjadi kesalahan saat menyimpan", 'error');
                    });
                }
            });
        });
        $('.btn-save-realisasi-my-activity').click(function (e) {
            e.preventDefault();
            let me = $(this);
            let title = me.attr('data-title');
            let id = me.attr('data-id');
            Swal.fire({
                title: 'Simpan realisasi',
                html: 'Yakin ingin menyimpan <h3>' + title + ' ?</h3>',
                type: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, simpan',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.value) {
                    var realisasi = $('.realisasi-myactivity[data-id='+id+']').val();
                    var keterangan = $('.keterangan-myactivity[data-id='+id+']').val();
                    var tingkat_kualitas = $('.tingkat-kualitas-myactivity[data-id='+id+']').val();
                    axios({
                        method: 'put',
                        url: '{{ url('/') }}/myactivity/update-my-activity/' + id,
                        data: {user_id: {{ auth()->user()->id }}, realisasi: realisasi, tingkat_kualitas: tingkat_kualitas, keterangan: keterangan}
                    }).then(function (res) {
                        let data = res.data;
                        if(data.status == 'sukses'){
                            Swal.fire({
                                title: 'Berhasil',
                                html: data.message,
                                type: 'success'
                            });
                        }else{
                            Swal.fire({
                                title: 'Gagal',
                                html: data.message,
                                type: 'failed'
                            });
                        }
                    }).catch(function (err) {
                        console.error(err);
                        Swal.fire('Gagal Menyimpan', "Terjadi kesalahan saat menyimpan", 'error');
                    });
                }
            });
        });
        $('.btn-delete-item-my-activity').click(function (e) {
            e.preventDefault();
            let me = $(this);
            let title = me.attr('title');
            let id = me.attr('id-item');
            Swal.fire({
                title: '',
                html: 'Yakin Ingin Menghapus <h3>' + title + ' ?</h3>',
                type: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus'
            }).then((result) => {
                if (result.value) {
                    axios({
                        method: 'delete',
                        url: '{{ url('/') }}/myactivity/' + id,
                    }).then(function (res) {
                        if (res.data.status == 'sukses'){
                            Swal.fire('Berhasil', res.data.message, 'success')
                                .then((result) => {
                                    if (result.value) {
                                        window.location.reload();
                                    }
                                });
                        }else{
                            Swal.fire('Gagal', res.data.message, 'error')
                                .then((result) => {
                                    if (result.value) {
                                        window.location.reload();
                                    }
                                });
                        }
                    }).catch(function (err) {
                        Swal.fire('Gagal Menghapus', "Terjadi kesalahan saat menghapus",
                            'error');
                    });
                }
            });
        });
        $("#select").change(function () {
            $("#formChange").submit();
        });
        $('input.realisasi').on('input', function () {
            var value = $(this).val();
            let maxValue = $(this).attr('max');
            if ((value !== '') && (value.indexOf('.') === -1)) {
                $(this).val(Math.max(Math.min(value, maxValue), 0));
            }
        });
        $(`
        input.realisasi,
        input.keterangan,
        input.tingkat-kualitas,
        input.realisasi-myactivity,
        input.keterangan-myactivity,
        input.tingkat-kualitas-myactivity
        `).blur(function(){
            $.notify({
                title: '<strong>Jangan lupa menyimpan</strong><br>',
                message: 'Perubahan belum disimpan sampai Anda menekan tombol simpan disebelah kanan'
            },{
                type: "info",
                allow_dismiss: true,
                newest_on_top: false,
                showProgressbar: false,
                placement: {
                    from: "bottom",
                    align: "right"
                },
                timer: 10,
            });
        });
    });

</script>
@endpush

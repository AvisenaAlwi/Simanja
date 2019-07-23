@inject('Input', 'Illuminate\Support\Facades\Input')
@inject('Carbon', '\Carbon\Carbon')
@inject('Activity', '\App\Activity')

@extends('layouts.app', ['showSearch' => true, 'title' => 'KegiatanKu'])
@push('style')

@endpush
@php
$months = config('scale.month');
$currentYear = $Carbon::now()->format('Y');
$monthQuery = $Input::get('month','now');
$yearQuery = $Input::get('year',$currentYear);
@endphp
@section('content')
@include('users.partials.header', [
'title' => 'KegiatanKu',
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
                                <a href="#" title="Cetak CKP-T Bulan {{ $monthQuery }} {{ $yearQuery }}" data-toggle="tooltip" data-placement="top" class="mr-3">
                                    <button type="button" class="btn btn-warning btn-sm">
                                        <i class="fa fa-print"></i>
                                        Cetak CKP-T
                                    </button>
                                </a>
                                <a href="#" title="Cetak CKP-R Bulan {{ $monthQuery }} {{ $yearQuery }}" data-toggle="tooltip" data-placement="top">
                                    <button type="button" class="btn btn-success btn-sm">
                                        <i class="fa fa-print"></i>
                                        Cetak CKP-R
                                    </button>
                                </a>
                                <a href="{{ route('myactivity.create') }}" 
                                    class="ml-3"
                                    title="Tambah kegiatan sendiri diluar yang ditetapkan supervisor" data-toggle="tooltip" data-placement="left">
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
                                <th>Completion</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody class="list">
                            @forelse ($sub_activity as $sub)
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
                                            <a href="#" data-toggle="tooltip"
                                                data-original-title="{{ $sub->users_name }}"
                                                class="avatar avatar-sm rounded-circle">
                                                <img alt="Image placeholder" src="{{ asset('img/theme/team-1-800x800.jpg') }}">
                                            </a>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center"><span class="completion mr-2">60%</span>
                                        <div>
                                            <div class="progress-wrapper pt-0">
                                                <!---->
                                                <div class="progress" style="height: 3px;">
                                                    <div role="progressbar" aria-valuenow="60" aria-valuemin="0"
                                                        aria-valuemax="100" class="progress-bar bg-warning"
                                                        style="width: 60%;"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-right">
                                    <li aria-haspopup="true" class="dropdown dropdown dropdown"><a role="button"
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
                                    </li>
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">
                                        <h3>Tidak ada kegiatan</h3>
                                    </td>
                                </tr>
                            @endforelse
                            <thead class="thead-light">
                                <tr>
                                    <th>Kegiatan yang saya tambahkan</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            @forelse ($my_activity as $activity)
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
                                            <a href="#" data-toggle="tooltip"
                                                data-original-title="{{ auth()->user()->name }}"
                                                class="avatar avatar-sm rounded-circle">
                                                <img alt="Image placeholder" src="{{ asset('img/theme/team-1-800x800.jpg') }}">
                                            </a>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center"><span class="completion mr-2">60%</span>
                                        <div>
                                            <div class="progress-wrapper pt-0">
                                                <div class="progress" style="height: 3px;">
                                                    <div role="progressbar" aria-valuenow="60" aria-valuemin="0"
                                                        aria-valuemax="100" class="progress-bar bg-warning"
                                                        style="width: 60%;"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-right">
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
                                <td colspan="5" class="text-center">
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
<script>
    $(document).ready(function () {
        $('.btn-delete-item').click(function (e) {
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
                        url: '{{ url('/') }}/activity/' + id
                    }).then(function (res) {
                        Swal.fire('Berhasil', title + " berhasil dihapus", 'success')
                            .then((result) => {
                                if (result.value) {
                                    window.location.reload();
                                }
                            });
                    }).catch(function (err) {
                        Swal.fire('Gagal Menghapus', "Terjadi kesalahan saat menghapus",
                            'error');
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
                        url: '{{ url('/') }}/myactivity/' + id
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
    });

</script>
@endpush

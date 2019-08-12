@inject('Input', 'Illuminate\Support\Facades\Input')
@inject('Carbon', '\Carbon\Carbon')

@extends('layouts.app', ['title' => 'Kegiatan'])
@push('style')
@php
$query = $Input::get('query','');
@endphp
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
'title' => 'Kegiatan',
'description' => 'Tabel berikut menunjukkan tabel kegiatan yang sudah ditambah ke sistem.'
])

<div class="container-fluid mt--7">
    <div class="row">
        <div class="col">
            <div class="card shadow">
                <div class="card-header border-0">
                    <form id="formChange" action="{{ route('activity.index') }}" method="get">
                    <div class="row align-items-center">
                        <div class="col-12 col-lg-3 my-1 my-lg-0">
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
                        <div class="col-12 col-lg-4 my-1 my-lg-0">
                                <div class="form-group mb-0">
                                    <div class="input-group text-dark">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text text-dark"
                                            onclick="$(this).parent().parent().parent().parent().submit()"
                                            ><i class="fas fa-search"></i></span>
                                        </div>
                                    <input class="form-control text-dark pl-2" placeholder="Cari berdasarkan nama" type="text" name="query"
                                    value="{{ $Input::get('query','') }}">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-12 col-lg-1 text-center my-1 my-lg-0">
                            <a href="{{ route('activity.create') }}"
                                title="Tambah kegiatan" data-toggle="tooltip" data-placement="top">
                                <button type="button"
                                    class="btn btn-primary btn-sm">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </a>
                        </div>
                        </form>
                    </div>
                </form>
                </div>
                <div class="table-responsive">
                    <table class="table tablesorter table align-items-center table-flush table-hover align-item-start" id="tabel" style="min-height: 150px">
                        <thead class="thead-light">
                            <tr>
                                <th>Nama Kegiatan</th>
                                <th>Waktu</th>
                                <th>Pembuat</th>
                                <th></th>
                            </tr>
                        </thead>
                        <div class="col-12">
                        @if (session('status'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('status') }}
                                <a href="{{ route('assignment.index', ['query'=>session('query'),'month'=>session('month'),'year'=>session('year')])}}"> Tugaskan sekarang?</a>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                    </div>
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
                                        </a>
                                    </div>
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
                                <td class="text-right">
                                    <li aria-haspopup="true" class="dropdown dropdown dropdown">
                                        <a role="button"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                            class="btn btn-sm btn-icon-only text-primary">
                                            <i class="fas fa-ellipsis-v" title="Aksi" data-toggle="tooltip" data-placement="left"></i>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-right">
                                            <a href="{{ route('activity.show', $sub->id) }}"
                                                class="dropdown-item"><i class="fa fa-info text-info"></i>Detail kegiatan</a>
                                            @if (auth()->user()->role_id == 1 || $sub->created_by_user_id == auth()->user()->id)
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
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-12 col-lg-4 d-flex justify-content-center justify-content-lg-start align-items-center">
                            <h4>Total : {{ $sub_activity->total() }} kegiatan</h4>
                        </div>
                        <div class="col-12 col-lg-8 d-flex justify-content-center justify-content-lg-end align-items-center">
                            {{ $sub_activity->appends(['showing'=>$showing, 'query'=>$query])->links() }}
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
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
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
        $("#select").change(function () {
            $("#formChange").submit();
        });
    });

</script>
@endpush

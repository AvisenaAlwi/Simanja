@inject('Input', 'Illuminate\Support\Facades\Input')
@inject('Carbon', '\Carbon\Carbon')
@inject('Activity', '\App\Activity')

@extends('layouts.app', ['title' => 'Kegiatan'])
@push('style')

@endpush
@section('content')
@include('users.partials.header', [
'title' => 'Pelaporan',
'description' => 'Laman penilaian terhadap pelaporan kegiatan yang sudah dilaksanakan.'
])

<div class="container-fluid mt--7">
    <div class="row">
        <div class="col">
            <div class="card shadow">
                <div class="card-header border-0">
                        <div class="row align-items-center">
                                <div class="col-12 col-lg-3 my-1 my-lg-0">
                                    <h3 class="mb-0">
                                        @if ($showing == 'showCreate')
                                        Kegiatan Yang Saya Buat
                                        @elseif ($showing == 'showMe')
                                        Kegiatan Yang Saya Emban
                                        @else
                                        Kegiatan Bulan
                                        {{ $Carbon::parse('this month')->formatLocalized('%B %Y') }}
                                        @endif
                                    </h3>
                                </div>
                                <div class="col-12 col-lg-4 my-1 my-lg-0">
                                    <form id="formChange" action="{{ route('report.index') }}" method="get">
                                        <select name="showing" id="select" class="browser-default custom-select">
                                            <option value="showCreate"  {{ $showing == 'showCreate' ? 'selected' :'' }}>Kegiatan Yang Saya Buat</option>
                                            <option value="showMe" {{ $showing == 'showMe' ? 'selected' :'' }}>Kegiatan Yang Saya Emban</option>
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
                </div>
                </div>
                <div class="table-responsive">
                    <table class="table tablesorter table align-items-center table-flush table-hover align-item-start" id="tabel" style="min-height: 150px">
                        <thead class="thead-light">
                            <tr>
                                <th>Nama Kegiatan</th>
                                <th>Waktu</th>
                                <th>Pembuat</th>
                                <th>Completion</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody class="list">
                            @forelse ($sub_activity as $sub)
                            <tr>
                                <th scope="row">
                                    <div class="media align-items-center">
                                        <a href="{{ route('report.show_pelaporan', $sub->id) }}">
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
                                                <img alt="Image placeholder" src="{{ asset('storage') }}/{{auth()->user()->photo}}">
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
                                    <li aria-haspopup="true" class="dropdown dropdown dropdown">
                                        <a role="button"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                            class="btn btn-sm btn-icon-only text-primary">
                                            <i class="fas fa-ellipsis-v" title="Aksi" data-toggle="tooltip" data-placement="left"></i>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-right">
                                            <a href="{{ route('report.show_pelaporan', $sub->id) }}"
                                                class="dropdown-item"><i class="fa fa-info text-info"></i>Detail kegiatan</a>
                                            @if (auth()->user()->role_id == 1 || $Activity::find($sub->activity_id)->create_by_user_id == auth()->user()->id)
                                            <a href="{{ route('activity.edit', $sub->id) }}"
                                                class="dropdown-item"><i class="fa fa-edit text-success"></i></a>
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
                        <thead class="thead-light">
                            <tr>
                                <th>Kegiatan yang saya tambahkan</th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td colspan="5" class="text-center"><h1>TODO biar gak lupa</h1></td></tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer d-flex justify-content-end">
                    {{ $sub_activity->links() }}
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
        $("#select").change(function () {
            $("#formChange").submit();
        });
    });

</script>
@endpush
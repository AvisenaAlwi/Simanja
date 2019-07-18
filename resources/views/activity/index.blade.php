@extends('layouts.app', ['showSearch' => true])
@push('style')

@endpush
@section('content')
@include('users.partials.header', [
'title' => 'Kegiatan',
'description' => __('Tabel berikut menunjukkan tabel kegiatan yang dapat ditambah ke sistem.'),
'class' => 'col-lg-7'
])

<div class="container-fluid mt--7">
    <div class="row">
        <div class="col">
            <div class="card shadow">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">
                                @if (Illuminate\Support\Facades\Input::get('all', 'false') == 'true')
                                Tabel Kegiatan
                                @else
                                Tabel Kegiatan Periode
                                {{ \Carbon\Carbon::parse('first day of this month')->format('d') }} -
                                {{ \Carbon\Carbon::parse('last day of this month')->formatLocalized('%d %B %Y') }}
                                @endif
                            </h3>
                        </div>
                        <form id="formChange" action="{{ route('activity.index') }}" method="get">
                            <select name="showing" id="select" class="browser-default custom-select">
                                <option value="showAll" {{ $showing == 'showAll' ? 'selected' :'' }}>Tampilkan semua
                                    kegiatan</option>
                                <option value="showOnlyMe" {{ $showing == 'showOnlyMe' ? 'selected' :'' }}>Tampilkan
                                    hanya yang saya buat</option>
                            </select>
                        </form>
                        <div class="col text-right">
                            <a href="{{ route('activity.create') }}"><button type="button"
                                    class="btn btn-primary btn-sm">Tambah</button></a>
                        </div>
                        </form>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table tablesorter table align-items-center table-flush table-hover" id="tabel">
                        <thead class="thead-light">
                            <tr>
                                <th>Nama Kegiatan</th>
                                <th>Status</th>
                                <th>Pembuat</th>
                                <th>Completion</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody class="list">
                            @foreach ($sub_activity as $sub)
                            @php
                            $full_name = $sub->sub_activity_name . " " . $sub->activity_name;
                            @endphp
                            <tr>
                                <th scope="row">
                                    <div class="media align-items-center">
                                        <a href="{{ route('activity.show', $sub->id) }}">
                                            <div class="media-body">
                                                <span class="name mb-0 text-sm">
                                                    {{ $full_name }}
                                                </span>
                                            </div>
                                    </div>
                                    </a>
                                </th>
                                <td><span class="badge badge-dot mr-4 badge-warning"><i class="bg-warning"></i><span
                                            class="status">pending</span></span></td>
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
                                            class="btn btn-sm btn-icon-only text-light"><i
                                                class="fas fa-ellipsis-v"></i></a>
                                        <ul class="dropdown-menu dropdown-menu-right">
                                            <a href="{{ route('activity.show', $sub->id) }}" class="dropdown-item">Detil
                                                kegiatan</a>
                                            <a href="{{ route('activity.edit', $sub->id) }}"
                                                class="dropdown-item">Edit</a>
                                            <a href="" class="dropdown-item btn-delete-item" title="{{ $full_name }}"
                                                id-item="{{ $sub->id }}" style="color: red;"><b>Hapus</b></a></ul>
                                    </li>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer d-flex justify-content-end">
                    {{ $sub_activity->links() }}
                    {{-- <ul class="pagination">
                        <li class="page-item prev-page disabled"><a aria-label="Previous" class="page-link"><span
                                    aria-hidden="true"><i aria-hidden="true" class="fa fa-angle-left"></i></span></a>
                        </li>
                        <li class="page-item active"><a class="page-link">1</a></li>
                        <li class="page-item"><a class="page-link">2</a></li>
                        <li class="page-item"><a class="page-link">3</a></li>
                        <li class="page-item next-page"><a aria-label="Next" class="page-link"><span
                                    aria-hidden="true"><i aria-hidden="true" class="fa fa-angle-right"></i></span></a>
                        </li>
                    </ul> --}}
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
                text: 'Yakin Ingin Menghapus ' + title + '?',
                type: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus'
            }).then((result) => {
                if (result.value) {
                    axios({
                        method: 'delete',
                        url: '{{ url(' / ') }}/activity/' + id
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

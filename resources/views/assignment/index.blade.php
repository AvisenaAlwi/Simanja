@extends('layouts.app', ['showSearch' => true])
@push('style')

@endpush
@section('content')
@include('users.partials.header', [
'title' => 'Penugasan'
])

<div class="container-fluid mt--7">
    <div class="row">
        <div class="col">
            <div class="card shadow">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col-12 col-lg-4 my-1 my-lg-0">
                            <h3 class="mb-0">
                                Tabel kegiatan untuk penugasan
                            </h3>
                        </div>
                        <form action="{{ route('assignment.index') }}" method="get" class="row col-12 col-lg-8 my-1 my-lg-0">
                            <div class="col-12 col-lg-6 my-1 my-lg-0">
                                <select name="showMonth" class="browser-default custom-select">
                                    <option value="showCurrentMonth"  {{ $showMonth == 'showCurrentMonth' ? 'selected' :'' }}>Tampilkan Untuk Bulan Saat Ini</option>
                                    <option value="showAllMonth" {{ $showMonth == 'showAllMonth' ? 'selected' :'' }}>Tampilkan Untuk Semua Bulan</option>
                                </select>

                            </div>
                            <div class="col-12 col-lg-6 my-1 my-lg-0">
                                <select name="show" class="browser-default custom-select">
                                    <option value="showAll"  {{ $show == 'showAll' ? 'selected' :'' }}>Tampilkan semua kegiatan</option>
                                    <option value="showAssignment" {{ $show == 'showAssignment' ? 'selected' :'' }}>Tampilkan kegiatan yang ditugaskan</option>
                                    <option value="showUnassignment" {{ $show == 'showUnassignment' ? 'selected' :'' }}>Tampilkan kegiatan yang belum ditugaskan</option>
                                </select>
                                
                            </div>
                        </form>
                    </form>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table tablesorter align-items-center table-flush table-hover" id="tabel">
                        <thead class="thead-light">
                            <tr>
                                <th>Nama Kegiatan</th>
                                <th>Status</th>
                                <th>Petugas</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody class="list">
                            @foreach ($sub_activity as $sub)
                            <tr>
                                <th scope="row">
                                    <div class="media align-items-center">
                                        <div class="media-body">
                                            <span class="name mb-0 text-sm">
                                                {{ $sub->full_name }}
                                            </span>
                                        </div>
                                    </div>
                                </th>
                                <td>
                                    @if (sizeof(json_decode($sub->petugas)) == 0)
                                        <span class="badge badge-dot mr-4 badge-warning">
                                            <i class="bg-warning"></i>
                                            <span class="status">Belum Ditugaskan</span>
                                        </span>
                                    @else
                                        <span class="badge badge-dot mr-4 badge-success">
                                            <i class="bg-success"></i>
                                            <span class="status">Ditugaskan</span>
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="avatar-group">
                                        @forelse (json_decode($sub->petugas) as $person)
                                        @php
                                            $person = App\User::find($person);
                                        @endphp
                                        <a href="#" data-toggle="tooltip"
                                            data-original-title="{{ $person->name }}"
                                            class="avatar avatar-sm rounded-circle">
                                            <img alt="Image placeholder" src="{{ asset('img/theme/team-1-800x800.jpg') }}">
                                        </a>
                                        @empty
                                            Tidak Ada Petugas
                                        @endforelse
                                    </div>
                                </td>
                                <td>
                                    @if (sizeof(json_decode($sub->petugas)) == 0)
                                        <a href="{{ route('assignment.edit', $sub->id) }}">
                                            <button class="btn btn-warning btn-block text-left">
                                                <i class="ni ni-single-copy-04"></i>
                                                <span>Tugaskan</span>
                                            </button>
                                        </a>
                                    @else
                                        <a href="{{ route('assignment.edit', $sub->id) }}">
                                            <div class="btn btn-success btn-block text-left">
                                                <i class="fas fa-edit"></i>
                                                <span>Edit Penugasan</span>
                                            </div>
                                        </a>
                                    @endif
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
        $(".custom-select").change(function(){
            $(this).parent().parent().submit();
        });
    });


</script>
@endpush

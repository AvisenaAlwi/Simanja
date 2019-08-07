@inject('Input', 'Illuminate\Support\Facades\Input')
@extends('layouts.app', ['title' => 'Daftar Pegawai'])
@push('style')

@endpush
@section('content')
@include('users.partials.header', [
'title' => 'Daftar Pegawai',
])

<div class="container-fluid mt--7">
    <div class="row">
        <div class="col">
            <div class="card shadow">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col-12 col-lg-3 my-1 my-lg-0">
                            <h3 class="mb-0">Pegawai</h3>
                        </div>
                        <div class="col-12 col-lg-4 my-1 my-lg-0">
                            <form id="formChange" action="{{ route('employees') }}" method="get">
                                <div class="form-group mb-0">
                                    <div class="input-group text-dark">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text text-dark"
                                                onclick="$(this).parent().parent().parent().parent().submit()"><i
                                                    class="fas fa-search"></i></span>
                                        </div>
                                        <input class="form-control text-dark pl-4"
                                            placeholder="Cari berdasarkan nama / NIP" type="text" name="query"
                                            value="{{ $Input::get('query','') }}">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">Nama</th>
                                <th scope="col">Jabatan</th>
                                <th scope="col">Kegiatan yang diemban bulan ini</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                            <tr>
                                <td>
                                    <a href="{{ route('employee', preg_replace('/\s+/', '', $user->nip)) }}">
                                        <img class="avatar avatar-lg rounded-circle mr-3"
                                            src="{{ asset('storage').'/'.$user->photo }}" alt="">
                                        <b>{{ $user->name }}</b>
                                    </a>
                                </td>
                                <td>
                                    {{ $user->jabatan }}
                                </td>
                                <td>
                                    <b>{{ $jumlahKegiatanYangDiemban[$user->id] }}</b> kegiatan
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer py-4">
                        <h4>Total : {{ $users->total() }} Pengguna</h4>
                    <nav class="d-flex justify-content-end" aria-label="...">
                            {{ $users->links() }}
                        </nav>
                    {{-- <nav class="d-flex justify-content-end" aria-label="...">
                            {{ $users->links() }}
                    </nav> --}}
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
<script>
    $(document).ready(function () {
        $('.btn-delete-user').click(function (e) {
            e.preventDefault();
            let me = $(this);
            let name = me.attr('name');
            Swal.fire({
                title: '',
                html: 'Yakin Ingin Menghapus <h3>' + name + ' ?</h3>',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.value)
                    $(this).parent().submit();
            }).catch((error) => {
                console.error(error);
            });
        });
    });

</script>
@endpush

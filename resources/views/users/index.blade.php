@inject('Input', 'Illuminate\Support\Facades\Input')
@inject('Carbon', '\Carbon\Carbon')
@inject('Activity', '\App\Activity')

@extends('layouts.app', ['title' => 'Manajemen Akun'])
@push('style')

@endpush
@section('content')
@include('users.partials.header', [
'title' => 'Manajemen Akun',
'description' => 'Tabel berikut menunjukkan tabel akun yang sudah ditambah ke sistem..'
])

    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col">
                <div class="card shadow">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">Akun Pengguna</h3>
                            </div>
                            <div class="col-4 text-right">
                            <a href="{{ route('user.create') }}"
                                title="Tambah Pengguna" data-toggle="tooltip" data-placement="top">
                                <button type="button"
                                    class="btn btn-primary btn-sm"><i class="fa fa-plus"></i>
                                </button>
                            </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        @if (session('status'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('status') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                    </div>

                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Tanggal registrasi</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>
                                            <img class="avatar avatar-lg rounded-circle mr-3" src="{{ asset('storage').'/'.$user->photo }}" alt="">
                                            <b>{{ $user->name }}</b>
                                        </td>
                                        <td>
                                            <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                                        </td>
                                        <td>{{ $user->created_at->timezone('Asia/Jakarta')->formatLocalized('%A, %d %B %Y %H:%M') }}</td>
                                        <td class="text-right">
                                            @if ($user->id != auth()->id())
                                                <form action="{{ route('user.destroy', $user) }}" method="post">
                                                    @csrf
                                                    @method('delete')

                                                    <a class="btn btn-primary" href="{{ route('user.edit', $user) }}"><i class="fa fa-edit text-white"></i> Edit</a>
                                                    <button type="button"
                                                    class="btn btn-danger btn-delete-user" name="{{ $user->name }}">
                                                        <i class="fa fa-trash text-white"></i> Hapus
                                                    </button>
                                                </form>
                                            @else
                                                <a class="btn btn-primary" href="{{ route('user.edit', $user) }}"><i class="fa fa-edit text-white"></i> Edit</a>
                                            @endif
                                            {{-- <div class="dropdown">
                                                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                    @if ($user->id != auth()->id())
                                                        <form action="{{ route('user.destroy', $user) }}" method="post">
                                                            @csrf
                                                            @method('delete')

                                                            <a class="dropdown-item" href="{{ route('user.edit', $user) }}"><i class="fa fa-edit text-success"></i> Edit</a>
                                                            <button type="button"
                                                            class="dropdown-item btn-delete-user" name="{{ $user->name }}">
                                                                <i class="fa fa-trash text-danger"></i> Hapus
                                                            </button>
                                                        </form>
                                                    @else
                                                        <a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="fa fa-edit text-success"></i> Edit</a>
                                                    @endif
                                                </div>
                                            </div> --}}
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
                    </div>
                </div>
            </div>
        </div>

        @include('layouts.footers.auth')
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
                if(result.value)
                    $(this).parent().submit();
            }).catch((error) => {
                console.error(error);
            });
        });
    });

</script>
@endpush

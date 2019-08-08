@inject('Input', 'Illuminate\Support\Facades\Input')
@inject('Carbon', '\Carbon\Carbon')
@extends('layouts.app', ['showSearch' => true])
@push('style')

@endpush
@php
$months = config('scale.month');
$currentYear = now()->year;
$currentMonth = $Carbon::now()->formatLocalized('%B');
$monthQuery = $Input::get('month', 'now');
$yearQuery = $Input::get('year', $currentYear);
@endphp
@section('content')
@include('users.partials.header', [
'title' => 'Penugasan'
])

<div class="container-fluid mt--7">
    <div class="row">
        <div class="col">
            <div class="card shadow">
                <div class="card-header border-0">
                    <div class="align-items-center">
                        <form action="{{ route('assignment.index') }}" method="get" class="row my-1 my-lg-0">
                            <div class="col-12 col-lg-3 my-1 my-lg-0">
                                <select name="month" class="browser-default custom-select">
                                    <option value="allMonth" {{ $monthQuery == 'allMonth' ? 'selected' :'' }}>Tampilkan Untuk Semua Bulan</option>
                                    @foreach ($months as $month)
                                        @if ($month == $currentMonth)
                                        <option value="now"  {{ $monthQuery == 'now' ? 'selected' :'' }}>Bulan sekarang</option>
                                        @else
                                        <option value="{{ $month }}" {{ $monthQuery == $month ? 'selected' :'' }}>{{ $month }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-lg-2 my-1 my-lg-0">
                                <select name="year" class="browser-default custom-select">
                                    @php $x = 2019; @endphp
                                    @while ($x <= $currentYear)
                                        <option value="{{ $x }}" {{ $x == $yearQuery ? 'selected' : '' }}>{{ $x }}</option>
                                        @php
                                        $x += 1;
                                        @endphp
                                    @endwhile
                                </select>
                            </div>
                            <div class="col-12 col-lg-3 my-1 my-lg-0">
                                <select name="show" class="browser-default custom-select">
                                    <option value="showAll"  {{ $show == 'showAll' ? 'selected' :'' }}>Tampilkan semua kegiatan</option>
                                    <option value="showAssignment" {{ $show == 'showAssignment' ? 'selected' :'' }}>Tampilkan kegiatan yang ditugaskan</option>
                                    <option value="showUnassignment" {{ $show == 'showUnassignment' ? 'selected' :'' }}>Tampilkan kegiatan yang belum ditugaskan</option>
                                </select>
                            </div>
                            <div class="col-12 col-lg-4 my-1 my-lg-0">
                                <div class="form-group mb-0">
                                    <div class="input-group text-dark">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text text-dark"
                                            onclick="$($(this).parents()[4]).submit()"
                                            ><i class="fas fa-search"></i></span>
                                        </div>
                                    <input class="form-control text-dark pl-2" placeholder="Cari berdasarkan nama" type="text" name="query"
                                    value="{{ $Input::get('query','') }}">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </form>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table tablesorter align-items-center table-flush table-hover" id="tabel" style="min-height: 150px">
                        <thead class="thead-light">
                            <tr>
                                <th>Nama Kegiatan</th>
                                <th>Status</th>
                                <th>Petugas</th>
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
                                    @if (sizeof(json_decode($sub->petugas, true)) == 0)
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
                                        @forelse (json_decode($sub->petugas, true) as $idPerson => $monthsArrays)
                                        @php
                                            $person = App\User::find($idPerson);
                                        @endphp
                                        <a href="#" data-toggle="tooltip"
                                            data-original-title="{{ $person->name }}"
                                            class="avatar avatar-sm rounded-circle">
                                            <img alt="Image placeholder" src="{{ asset('storage') }}/{{ $person->photo }}">
                                        </a>
                                        @empty
                                            Tidak Ada Petugas
                                        @endforelse
                                    </div>
                                </td>
                                <td>
                                    @if($sub->created_by_user_id == auth()->user()->id || auth()->user()->role_id == 1)
                                    @empty(json_decode($sub->petugas))
                                        {{-- <a href="{{ route('assignment.edit', App\Assignment::where('sub_activity_id', '=', $sub->id)->select('id')->first()) }}"> --}}
                                        <a href="{{ route('assignment.edit', $sub->assignment_id) }}">
                                            <button class="btn btn-warning btn-block text-left">
                                                <i class="ni ni-single-copy-04"></i> Tugaskan
                                            </button>
                                        </a>
                                    @else
                                        <a href="{{ route('assignment.edit', $sub->id) }}">
                                            <div class="btn btn-success btn-block text-left">
                                                <i class="fas fa-edit"></i> Edit Penugasan
                                            </div>
                                        </a>
                                    @endempty
                                    @else
                                    Harus ditugaskan oleh {{ $sub->user_name }}
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">
                                    <h3>Tidak ada penugasan</h3>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-12 col-lg-4 d-flex justify-content-center justify-content-lg-start align-items-center">
                            <h4>Total : {{ $sub_activity->total() }} penugasan</h4>
                        </div>
                        <div class="col-12 col-lg-8 d-flex justify-content-center justify-content-lg-end align-items-center">
                            {{ $sub_activity->links() }}
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
<script>
    $(document).ready(function () {
        $(".custom-select").change(function(){
            $(this).parent().parent().submit();
        });
    });
</script>
@endpush
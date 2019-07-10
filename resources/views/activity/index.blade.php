@extends('layouts.app')
@push('style')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
@endpush
@push('js')
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
    <script>
        $(document).ready( function () {
            $('#table').DataTable();
        } );
    </script>
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
                                    Tabel Kegiatan Periode {{ \Carbon\Carbon::parse('first day of this month')->format('d') }} - 
                                    {{ \Carbon\Carbon::parse('last day of this month')->formatLocalized('%d %B %Y') }} 
                                @endif
                            </h3>
                        </div>
                        <div class="col text-right">
                            <a href="{{ route('activity.create') }}"><button type="button" class="btn btn-primary btn-sm">Tambah</button></a>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table tablesorter table align-items-center table-flush" id="tabel">
                        <thead class="thead-light">
                            <tr>
                                <th>Nama Kegiatan</th>
                                <th>Status</th>
                                <th>Users</th>
                                <th>Completion</th>
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
                                                    {{ $sub->sub_activity_name . " " . $sub->activity_name . " " . $sub->tahun}}
                                                </span>
                                            </div>
                                        </div>
                                    </th>
                                    {{-- <td class="budget">
                                        {{ $sub->tahun }}
                                    </td> --}}
                                    <td><span class="badge badge-dot mr-4 badge-warning"><i class="bg-warning"></i><span
                                                class="status">pending</span></span></td>
                                    <td>
                                        <div class="avatar-group"><a href="#" data-toggle="tooltip"
                                                data-original-title="Ryan Tompson"
                                                class="avatar avatar-sm rounded-circle"><img alt="Image placeholder"
                                                    src="img/theme/team-1-800x800.jpg"></a><a href="#" data-toggle="tooltip"
                                                data-original-title="Romina Hadid"
                                                class="avatar avatar-sm rounded-circle"><img alt="Image placeholder"
                                                    src="img/theme/team-2-800x800.jpg"></a><a href="#" data-toggle="tooltip"
                                                data-original-title="Alexander Smith"
                                                class="avatar avatar-sm rounded-circle"><img alt="Image placeholder"
                                                    src="img/theme/team-3-800x800.jpg"></a><a href="#" data-toggle="tooltip"
                                                data-original-title="Jessica Doe"
                                                class="avatar avatar-sm rounded-circle"><img alt="Image placeholder"
                                                    src="img/theme/team-4-800x800.jpg"></a></div>
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
                                            <ul class="dropdown-menu dropdown-menu-right"><a href="#"
                                                    class="dropdown-item">Edit</a><a href="#"
                                                    class="dropdown-item">Hapus</a></ul>
                                        </li>
                                    </td>
                                </tr>
                            @endforeach
                            {{-- <tr>
                                <th scope="row">
                                    <div class="media align-items-center"><a href="#"
                                            class="avatar rounded-circle mr-3"><img alt="Image placeholder"
                                                src="img/theme/angular.jpg"></a>
                                        <div class="media-body"><span class="name mb-0 text-sm">Angular Now UI Kit
                                                PRO</span></div>
                                    </div>
                                </th>
                                <td class="budget">
                                    $1800 USD
                                </td>
                                <td><span class="badge badge-dot mr-4 badge-success"><i class="bg-success"></i><span
                                            class="status">completed</span></span></td>
                                <td>
                                    <div class="avatar-group"><a href="#" data-toggle="tooltip"
                                            data-original-title="Ryan Tompson"
                                            class="avatar avatar-sm rounded-circle"><img alt="Image placeholder"
                                                src="img/theme/team-1-800x800.jpg"></a><a href="#" data-toggle="tooltip"
                                            data-original-title="Romina Hadid"
                                            class="avatar avatar-sm rounded-circle"><img alt="Image placeholder"
                                                src="img/theme/team-2-800x800.jpg"></a><a href="#" data-toggle="tooltip"
                                            data-original-title="Alexander Smith"
                                            class="avatar avatar-sm rounded-circle"><img alt="Image placeholder"
                                                src="img/theme/team-3-800x800.jpg"></a><a href="#" data-toggle="tooltip"
                                            data-original-title="Jessica Doe"
                                            class="avatar avatar-sm rounded-circle"><img alt="Image placeholder"
                                                src="img/theme/team-4-800x800.jpg"></a></div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center"><span class="completion mr-2">100%</span>
                                        <div>
                                            <div class="progress-wrapper pt-0">
                                                <!---->
                                                <div class="progress" style="height: 3px;">
                                                    <div role="progressbar" aria-valuenow="100" aria-valuemin="0"
                                                        aria-valuemax="100" class="progress-bar bg-success"
                                                        style="width: 100%;"></div>
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
                                        <ul class="dropdown-menu dropdown-menu-right"><a href="#"
                                                class="dropdown-item">Action</a><a href="#"
                                                class="dropdown-item">Another action</a><a href="#"
                                                class="dropdown-item">Something else here</a></ul>
                                    </li>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <div class="media align-items-center"><a href="#"
                                            class="avatar rounded-circle mr-3"><img alt="Image placeholder"
                                                src="img/theme/bootstrap.jpg"></a>
                                        <div class="media-body"><span class="name mb-0 text-sm">Argon Design
                                                System</span></div>
                                    </div>
                                </th>
                                <td class="budget">
                                    2019
                                </td>
                                <td><span class="badge badge-dot mr-4 badge-warning"><i class="bg-warning"></i><span
                                            class="status">pending</span></span></td>
                                <td>
                                    <div class="avatar-group"><a href="#" data-toggle="tooltip"
                                            data-original-title="Ryan Tompson"
                                            class="avatar avatar-sm rounded-circle"><img alt="Image placeholder"
                                                src="img/theme/team-1-800x800.jpg"></a><a href="#" data-toggle="tooltip"
                                            data-original-title="Romina Hadid"
                                            class="avatar avatar-sm rounded-circle"><img alt="Image placeholder"
                                                src="img/theme/team-2-800x800.jpg"></a><a href="#" data-toggle="tooltip"
                                            data-original-title="Alexander Smith"
                                            class="avatar avatar-sm rounded-circle"><img alt="Image placeholder"
                                                src="img/theme/team-3-800x800.jpg"></a><a href="#" data-toggle="tooltip"
                                            data-original-title="Jessica Doe"
                                            class="avatar avatar-sm rounded-circle"><img alt="Image placeholder"
                                                src="img/theme/team-4-800x800.jpg"></a></div>
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
                                        <ul class="dropdown-menu dropdown-menu-right"><a href="#"
                                                class="dropdown-item">Action</a><a href="#"
                                                class="dropdown-item">Another action</a><a href="#"
                                                class="dropdown-item">Something else here</a></ul>
                                    </li>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <div class="media align-items-center"><a href="#"
                                            class="avatar rounded-circle mr-3"><img alt="Image placeholder"
                                                src="img/theme/angular.jpg"></a>
                                        <div class="media-body"><span class="name mb-0 text-sm">Angular Now UI Kit
                                                PRO</span></div>
                                    </div>
                                </th>
                                <td class="budget">
                                    $1800 USD
                                </td>
                                <td><span class="badge badge-dot mr-4 badge-success"><i class="bg-success"></i><span
                                            class="status">completed</span></span></td>
                                <td>
                                    <div class="avatar-group"><a href="#" data-toggle="tooltip"
                                            data-original-title="Ryan Tompson"
                                            class="avatar avatar-sm rounded-circle"><img alt="Image placeholder"
                                                src="img/theme/team-1-800x800.jpg"></a><a href="#" data-toggle="tooltip"
                                            data-original-title="Romina Hadid"
                                            class="avatar avatar-sm rounded-circle"><img alt="Image placeholder"
                                                src="img/theme/team-2-800x800.jpg"></a><a href="#" data-toggle="tooltip"
                                            data-original-title="Alexander Smith"
                                            class="avatar avatar-sm rounded-circle"><img alt="Image placeholder"
                                                src="img/theme/team-3-800x800.jpg"></a><a href="#" data-toggle="tooltip"
                                            data-original-title="Jessica Doe"
                                            class="avatar avatar-sm rounded-circle"><img alt="Image placeholder"
                                                src="img/theme/team-4-800x800.jpg"></a></div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center"><span class="completion mr-2">100%</span>
                                        <div>
                                            <div class="progress-wrapper pt-0">
                                                <!---->
                                                <div class="progress" style="height: 3px;">
                                                    <div role="progressbar" aria-valuenow="100" aria-valuemin="0"
                                                        aria-valuemax="100" class="progress-bar bg-success"
                                                        style="width: 100%;"></div>
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
                                        <ul class="dropdown-menu dropdown-menu-right"><a href="#"
                                                class="dropdown-item">Action</a><a href="#"
                                                class="dropdown-item">Another action</a><a href="#"
                                                class="dropdown-item">Something else here</a></ul>
                                    </li>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <div class="media align-items-center"><a href="#"
                                            class="avatar rounded-circle mr-3"><img alt="Image placeholder"
                                                src="img/theme/sketch.jpg"></a>
                                        <div class="media-body"><span class="name mb-0 text-sm">Black Dashboard</span>
                                        </div>
                                    </div>
                                </th>
                                <td class="budget">
                                    $3150 USD
                                </td>
                                <td><span class="badge badge-dot mr-4 badge-danger"><i class="bg-danger"></i><span
                                            class="status">delayed</span></span></td>
                                <td>
                                    <div class="avatar-group"><a href="#" data-toggle="tooltip"
                                            data-original-title="Ryan Tompson"
                                            class="avatar avatar-sm rounded-circle"><img alt="Image placeholder"
                                                src="img/theme/team-1-800x800.jpg"></a><a href="#" data-toggle="tooltip"
                                            data-original-title="Romina Hadid"
                                            class="avatar avatar-sm rounded-circle"><img alt="Image placeholder"
                                                src="img/theme/team-2-800x800.jpg"></a><a href="#" data-toggle="tooltip"
                                            data-original-title="Alexander Smith"
                                            class="avatar avatar-sm rounded-circle"><img alt="Image placeholder"
                                                src="img/theme/team-3-800x800.jpg"></a><a href="#" data-toggle="tooltip"
                                            data-original-title="Jessica Doe"
                                            class="avatar avatar-sm rounded-circle"><img alt="Image placeholder"
                                                src="img/theme/team-4-800x800.jpg"></a></div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center"><span class="completion mr-2">72%</span>
                                        <div>
                                            <div class="progress-wrapper pt-0">
                                                <!---->
                                                <div class="progress" style="height: 3px;">
                                                    <div role="progressbar" aria-valuenow="72" aria-valuemin="0"
                                                        aria-valuemax="100" class="progress-bar bg-danger"
                                                        style="width: 72%;"></div>
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
                                        <ul class="dropdown-menu dropdown-menu-right"><a href="#"
                                                class="dropdown-item">Action</a><a href="#"
                                                class="dropdown-item">Another action</a><a href="#"
                                                class="dropdown-item">Something else here</a></ul>
                                    </li>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <div class="media align-items-center"><a href="#"
                                            class="avatar rounded-circle mr-3"><img alt="Image placeholder"
                                                src="img/theme/react.jpg"></a>
                                        <div class="media-body"><span class="name mb-0 text-sm">React Material
                                                Dashboard</span></div>
                                    </div>
                                </th>
                                <td class="budget">
                                    $4400 USD
                                </td>
                                <td><span class="badge badge-dot mr-4 badge-info"><i class="bg-info"></i><span
                                            class="status">on schedule</span></span></td>
                                <td>
                                    <div class="avatar-group"><a href="#" data-toggle="tooltip"
                                            data-original-title="Ryan Tompson"
                                            class="avatar avatar-sm rounded-circle"><img alt="Image placeholder"
                                                src="img/theme/team-1-800x800.jpg"></a><a href="#" data-toggle="tooltip"
                                            data-original-title="Romina Hadid"
                                            class="avatar avatar-sm rounded-circle"><img alt="Image placeholder"
                                                src="img/theme/team-2-800x800.jpg"></a><a href="#" data-toggle="tooltip"
                                            data-original-title="Alexander Smith"
                                            class="avatar avatar-sm rounded-circle"><img alt="Image placeholder"
                                                src="img/theme/team-3-800x800.jpg"></a><a href="#" data-toggle="tooltip"
                                            data-original-title="Jessica Doe"
                                            class="avatar avatar-sm rounded-circle"><img alt="Image placeholder"
                                                src="img/theme/team-4-800x800.jpg"></a></div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center"><span class="completion mr-2">90%</span>
                                        <div>
                                            <div class="progress-wrapper pt-0">
                                                <!---->
                                                <div class="progress" style="height: 3px;">
                                                    <div role="progressbar" aria-valuenow="90" aria-valuemin="0"
                                                        aria-valuemax="100" class="progress-bar bg-info"
                                                        style="width: 90%;"></div>
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
                                        <ul class="dropdown-menu dropdown-menu-right"><a href="#"
                                                class="dropdown-item">Action</a><a href="#"
                                                class="dropdown-item">Another action</a><a href="#"
                                                class="dropdown-item">Something else here</a></ul>
                                    </li>
                                </td>
                            </tr>
                             --}}
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

@extends('layouts.app', ['title' => 'Dashboard'])
@inject('Carbon', '\Carbon\Carbon')

@section('content')
<div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
    <div class="container-fluid">
        <div class="header-body">
            <!-- Card stats -->
            <div class="row">
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Pegawai <br> Aktif</h5>
                                    <span class="h1 font-weight-bold mb-0">{{ $activeUsers }}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                                        <i class="fas fa-users"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                @if ($usersPercent > 0)
                                <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> {{ number_format($usersPercent, 2) }}%</span>
                                @elseif ($usersPercent < 0)
                                <span class="text-danger mr-2"><i class="fa fa-arrow-down"></i> {{ number_format($usersPercent, 2) }}%</span>
                                @else
                                <span class="text-primary mr-2"><i class="fa fa-minus"></i> {{ number_format($usersPercent, 2) }}%</span>
                                @endif
                                <span class="text-nowrap">Sejak tahun lalu</span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Pegawai <br> Ditugaskan</h5>
                                    <span class="h1 font-weight-bold mb-0">{{ $userDitugaskan }}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                                        <i class="fas fa-tasks"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                {{-- <span class="text-danger mr-2"><i class="fas fa-arrow-down"></i> 3.48%</span> --}}
                                <span class="text-nowrap">Bulan Sekarang</span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Jumlah <br> Kegiatan</h5>
                                    <span class="h1 font-weight-bold mb-0">{{ $totalSubActivity }}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-yellow text-white rounded-circle shadow">
                                        <i class="fas fa-list"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                {{-- <span class="text-warning mr-2"><i class="fas fa-arrow-down"></i> </span> --}}
                                <span class="text-nowrap">Bulan sekarang</span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Realisasi <br> Berjalan</h5>
                                    <span class="h1 font-weight-bold mb-0">{{ number_format($alokasiBulanSekarang==0? 0 :$realisasiBulanSekarang/$alokasiBulanSekarang * 100) }}%</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-info text-white rounded-circle shadow">
                                        <i class="fas fa-percent"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                {{-- <span class="text-success mr-2"><i class="fas fa-arrow-up"></i> 12%</span> --}}
                                <span class="text-nowrap">dari <b class="text-bold">{{ $alokasiBulanSekarang }}</b> alokasi di bulan sekarang.</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mt--7">
    <div class="row">
        <div class="col-xl-8 mb-5 mb-xl-0">
            <div class="card bg-gradient-default shadow">
                <div class="card-header bg-transparent">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="text-uppercase text-light ls-1 mb-1"> 6 bulan terakhir</h6>
                            <h2 class="text-white mb-0">Realisasi</h2>
                        </div>
                        {{-- <div class="col">
                            <ul class="nav nav-pills justify-content-end">
                                <li class="nav-item mr-2 mr-md-0" data-toggle="chart" data-target="#chart-sales"
                                    data-update='{"data":{"datasets":[{"data":[0, 20, 10, 30, 15, 40, 20, 60, 60]}]}}'
                                    data-prefix="$" data-suffix="k">
                                    <a href="#" class="nav-link py-2 px-3 active" data-toggle="tab">
                                        <span class="d-none d-md-block">Month</span>
                                        <span class="d-md-none">M</span>
                                    </a>
                                </li>
                                <li class="nav-item" data-toggle="chart" data-target="#chart-sales"
                                    data-update='{"data":{"datasets":[{"data":[0, 20, 5, 25, 10, 30, 15, 40, 40]}]}}'
                                    data-prefix="$" data-suffix="k">
                                    <a href="#" class="nav-link py-2 px-3" data-toggle="tab">
                                        <span class="d-none d-md-block">Week</span>
                                        <span class="d-md-none">W</span>
                                    </a>
                                </li>
                            </ul>
                        </div> --}}
                    </div>
                </div>
                <div class="card-body">
                    <!-- Chart -->
                    <div class="chart">
                        <!-- Chart wrapper -->
                        <canvas id="chart-realisasi" class="chart-canvas"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card shadow">
                <div class="card-header bg-transparent">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="text-uppercase text-muted ls-1 mb-1">6 Bulan terakhir</h6>
                            <h2 class="mb-0">Jumlah Kegiatan</h2>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Chart -->
                    <div class="chart">
                        <canvas id="chart-activity" class="chart-canvas"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="row mt-5">
        <div class="col-xl-8 mb-5 mb-xl-0">
            <div class="card shadow">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col-5">
                            @php
                            $tenggatWaktu = $Carbon::now()->timezone('Asia/Jakarta')->diff($Carbon::now()->timezone('Asia/Jakarta')->endOfMonth())->d;
                            // dd($myAssignment);
                            $userID = auth()->user()->id;
                            $dayNow = (int)$Carbon::now()->timezone('Asia/Jakarta')->format( 'd' );
                            $dateNowFull = $Carbon::now()->timezone('Asia/Jakarta')->format( 'Y-m-d' );
                            // $Carbon::parse($assignmnent->update)->format('Y-m-d')
                            // dd($dayNow);
                            $dateNow = $Carbon::now()->timezone('Asia/Jakarta')->formatLocalized( '%B_%Y' );
                            $date = $Carbon::now()->timezone('Asia/Jakarta')->formatLocalized( '%e %B %Y' );
                            $day = $Carbon::now()->timezone('Asia/Jakarta')->formatLocalized( '%A' );
                            $lastMonth = $Carbon::now()->subMonth()->timezone('Asia/Jakarta')->formatLocalized( '%B' );
                            $thisYear = $Carbon::now()->timezone('Asia/Jakarta')->format('Y');
                            $dateCollapse = str_replace("_"," ",$dateNow);
                            $kegiatanCounter = 0;
                            $pengingatCounter = 0;
                            @endphp
                            <h3 class="mb-0">Kegiatanku {{ $dateCollapse }}</h3>
                            <h5>Tenggat waktu :
                                {{-- @if ($tenggatWaktu <= 5)
                                <div class="text-danger">
                                @else
                                <div class="">
                                    @endif --}}
                                <span class="{{ $tenggatWaktu <= 5 ? 'text-danger' : ''}}"> {{ $tenggatWaktu.' hari' }}
                                </span>
                            </h5>
                        </div>
                        <div class="col-4">
                            @if ($dayNow <= 5)
                                {{-- <h5>Jangan lupa print CKP-T dan CKP-R bulan {{$lastMonth.' '.$thisYear}} --}}
                                {{-- </h5> --}} @endif </div> <div class="col-1 text-right">
                                <a href="{{ route('myactivity.index') }}"
                                    class="btn btn-sm btn-primary" title="Lihat Kegiatanku" data-toggle="tooltip" data-placement="top">Selengkapnya</a>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <!-- Projects table -->
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th>Nama Kegiatan</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($myAssignment as $assignmnent)
                            @php
                            $realisasi = (int)json_decode($assignmnent->realisasi)->$userID->$dateNow;
                            $volume = (int)json_decode($assignmnent->petugas)->$userID->$dateNow;
                            // dd($volume - $realisasi);
                            @endphp
                            @if ($volume - $realisasi > 0)
                            @php
                            $kegiatanCounter++;
                            @endphp
                            <tr>
                                <th scope="">
                                    <a href={{ route('activity.show', $assignmnent->sub_activity) }}>
                                        {{$assignmnent->full_name}}
                                    </a>
                                </th>
                                <td>
                                    @if ($Carbon::parse($assignmnent->awal)->format('Y-m') ==
                                    $Carbon::parse($assignmnent->akhir)->format('Y-m') || $assignmnent->akhir == null)
                                    {{ $Carbon::parse($assignmnent->awal)->formatLocalized('%b %Y') }}
                                    @else
                                    {{ $Carbon::parse($assignmnent->awal)->formatLocalized('%b %Y') . ' - ' . $Carbon::parse($assignmnent->akhir)->formatLocalized('%b %Y') }}
                                    @endif
                                </td>
                                {{-- <td>
                                    <i class="fas fa-arrow-up text-success mr-3"></i> 46,53%
                                </td> --}}
                            </tr>
                            @endif
                            @endforeach
                            <tr>
                                <td colspan="2">
                                @if ($kegiatanCounter == 0)
                                <h3 class="text-center">Untuk saat ini semua kegiatan terselesaikan</h3>
                                @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card shadow" style="overflow: hidden">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0"></h3>
                            <i class="far fa-calendar"></i>
                            <span> <b>{{ $day }}</b></span>
                            <h3> {{ $date }}</h3>
                        </div>
                    </div>
                </div>
                <div>
                    <h3 class="text-center bg-secondary p-1" style="margin-bottom: 0 !important">Pengingat</h3>
                    @if ($tenggatWaktu <= 5)
                        @php
                            $pengingatCounter++;
                        @endphp
                        <div class="bg-danger p-3" style="border-bottom: 1px solid rgba(0,0,0,0.1)">
                            <a href="{{ route('myactivity.index') }}">
                                <h3 class="text-white">{{ $tenggatWaktu }} hari tersisa bulan ini</h3>
                                <h5 class="text-white">Segera isi realisasi!</h5>
                            </a>
                        </div>
                        {{-- @elseif ($dayNow <= 5)
                        @php
                            $pengingatCounter++;
                        @endphp
                        <tr class="table-success">
                            <td>
                                <a href="{{ route('activity.show', $assignment->sub_activity_id) }}">
                                    <h3 class="text-white">Anda baru saja ditugaskan!</h3>
                                    <h5 class="text-white"> {{ $assignment->full_name }}<h5>
                                            <h5 class="text-white">
                                                {{ $Carbon::parse($assignment->update)->timezone('Asia/Jakarta')->diffForHumans() }}
                                            </h5>
                                </a>
                            </td>
                        </tr> --}}
                    @endif
                    @foreach ($myAssignment as $assignment)
                        @php
                        if($assignment->update_state == 0)
                            continue;
                        $date = $Carbon::parse($assignment->update)->timezone('Asia/Jakarta');
                        //   dd($Carbon::now()->timezone('Asia/Jakarta')->format('d'), $Carbon::now()->timezone('Asia/Jakarta')->diffInDays($date), $date->format('d'))
                        @endphp
                        @if ($Carbon::now()->timezone('Asia/Jakarta')->diff($date)->d < 1)
                        @php
                            $pengingatCounter++;
                        @endphp
                        <div class='bg-success p-4' style="border-bottom: 1px solid rgba(0,0,0,0.1)">
                            <a href="{{ route('myactivity.index') }}">
                                @if ($assignment->update_state == 1 && $assignment->init_assign == true)
                                <h3 class="text-white">Anda baru saja ditugaskan!</h3>
                                @elseif ($assignment->update_state == 2 && $assignment->init_assign == false)
                                <h3 class="text-white">Anda baru saja ditugaskan ulang, harap cek kembali target dan realisasi!</h3>
                                @endif
                                <h5 class="text-white"> {{$assignment->full_name}}</h5>
                                <h5 class="text-white">{{ $Carbon::parse($assignment->update)->timezone('Asia/Jakarta')->diffForHumans() }}</h5>
                            </a>
                        </div>
                        @endif
                    @endforeach
                    @if ($pengingatCounter == 0)
                    <tr>
                        <td>
                            <h3 class="text-center my-5">Selamat bekerja</h3>
                        </td>
                    </tr>
                    @endif
                    
                </div>
            </div>
        </div>
    </div>

    @include('layouts.footers.auth')
</div>
@endsection

@push('js')
<script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.min.js"></script>
<script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.extension.js"></script>
@endpush
@push('js_suffix')
<script>
let cRealisasi = $('#chart-realisasi');
let realisasiChart = new Chart(cRealisasi, {
    type: 'bar',
    options: {
        scales: {
            yAxes: [{
                gridLines: {
                    color: Charts.colors.gray[900],
                    zeroLineColor: Charts.colors.gray[900]
                },
                ticks: {
                    callback: function(value) {
                        if (!(value % 10)) {
                            return value + '%';
                        }
                    }
                }
            }]
        },
        tooltips: {
            callbacks: {
                label: function(item, data) {
                    var label = data.datasets[item.datasetIndex].label || '';
                    var yLabel = item.yLabel;
                    var content = '';

                    if (data.datasets.length > 1) {
                        content += '<span class="popover-body-label mr-auto">' + label + '</span>';
                    }

                    content += '<span class="popover-body-value">' + yLabel + '%</span>';
                    return content;
                }
            }
        }
    },
    data: {
        labels: {!! json_encode($monthLabel) !!},
        datasets: [{
            label: 'Realisasi',
            data: {!! json_encode($monthRealisasiValue) !!}
        }]
    }
});
// Save to jQuery object
cRealisasi.data('chart', realisasiChart);

let cActivity = $('#chart-activity');
let chartactivity = new Chart($('#chart-activity'), {
    type: 'bar',
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    callback: function(value) {
                        if (!(value % 5)) {
                            return value
                        }
                    }
                }
            }]
        },
        tooltips: {
            callbacks: {
                label: function(item, data) {
                    var label = data.datasets[item.datasetIndex].label || '';
                    var yLabel = item.yLabel;
                    var content = '';

                    if (data.datasets.length > 1) {
                        content += '<span class="popover-body-label mr-auto">' + label + '</span>';
                    }

                    content += '<span class="popover-body-value">' + yLabel + ' kegiatan</span>';
                    
                    return content;
                }
            }
        }
    },
    data: {
        labels: {!! json_encode($monthLabel) !!},
        datasets: [{
            label: 'Kegiatan',
            data: {!! json_encode($activityCountMonthValue) !!}
        }]
    }
});
// Save to jQuery object
cActivity.data('chart', chartactivity);
</script>
@endpush

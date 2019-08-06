@extends('layouts.app', ['title' => 'Dashboard'])
@inject('Carbon', '\Carbon\Carbon')

@section('content')
@include('layouts.headers.cards')

<div class="container-fluid mt--7">
    <div class="row">
        <div class="col-xl-8 mb-5 mb-xl-0">
            <div class="card bg-gradient-default shadow">
                <div class="card-header bg-transparent">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="text-uppercase text-light ls-1 mb-1">Overview</h6>
                            <h2 class="text-white mb-0">Sales value</h2>
                        </div>
                        <div class="col">
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
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Chart -->
                    <div class="chart">
                        <!-- Chart wrapper -->
                        <canvas id="chart-sales" class="chart-canvas"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card shadow">
                <div class="card-header bg-transparent">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="text-uppercase text-muted ls-1 mb-1">Performance</h6>
                            <h2 class="mb-0">Total orders</h2>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Chart -->
                    <div class="chart">
                        <canvas id="chart-orders" class="chart-canvas"></canvas>
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
                            $date = $Carbon::now()->timezone('Asia/Jakarta')->formatLocalized( '%d %B %Y' );
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
            <div class="card shadow">
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
                <div class="table-responsive">
                    <!-- Projects table -->
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col" class="text-center">Pengingat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($tenggatWaktu <= 5) <tr>
                                @php
                                    $pengingatCounter++;
                                @endphp
                                <td class="bg-danger">
                                    <a href="{{ route('myactivity.index') }}">
                                        <h3 class="text-white">{{ $tenggatWaktu }} hari tersisa bulan ini</h3>
                                        <h5 class="text-white">Segera isi realisasi!</h5>
                                    </a>
                                </td>
                                </tr>
                                @elseif ($dayNow <= 5)
                                @php
                                    $pengingatCounter++;
                                @endphp
                                <tr class="table-success">
                                    <td>
                                        <a href="{{ route('myactivity.index') }}">
                                            <h3>Bulan baru, Semangat Baru!</h3>
                                            <h5>Jangan lupa print CKP-T dan CKP-R ya!</h5>
                                        </a>
                                    </td>
                                    </tr>
                                    @endif
                                    @foreach ($myAssignment as $assignment)
                                    @php
                                    $date = $Carbon::parse($assignment->update)->timezone('Asia/Jakarta');
                                    //   dd($Carbon::now()->timezone('Asia/Jakarta')->format('d'), $Carbon::now()->timezone('Asia/Jakarta')->diffInDays($date), $date->format('d'))
                                    @endphp
                                    @if ($Carbon::now()->timezone('Asia/Jakarta')->diff($date)->d < 1)
                                    @php
                                        $pengingatCounter++;
                                    @endphp
                                    <tr class='bg-success'>
                                        <td>
                                            <a href="{{ route('myactivity.index') }}">
                                                <h3>Anda baru saja ditugaskan!</h3>
                                                <h5> {{$assignment->name}}<h5>
                                                        <h5>{{ $Carbon::parse($assignment->update)->timezone('Asia/Jakarta')->diffForHumans() }}</h5>
                                            </a>
                                        </td>
                                    </tr>
                                    @endif
                                    @endforeach
                                    @if ($pengingatCounter == 0)
                                    <tr>
                                        <td>
                                    <h3 class="text-center">Selamat bekerja</h3>
                                        </td>
                                    </tr>
                                    @endif
                                </tbody>
                    </table>
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

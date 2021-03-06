<!DOCTYPE html>
<html lang="en">
@inject('Carbon', '\Carbon\Carbon')

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CKPR</title>
    <style>
        @page { margin: 0 5%; }
        body {
            font-family: "Arial", Helvetica, sans-serif;
            font-size: 12px;
            margin: 3%;
        }

        #title {
            text-align: center;
            margin: 0;
        }

        #reportType {
            text-align: center;
            width: 100px;
            border: 4px solid black;
            margin-left: 85%
        }

        /* table {
            text-align: center;
        } */

        .kategori,
        .uraian_keg,
        .keterangan {
            text-align: left;
        }

        #sign_dinilai {
            text-align: center;
            width: 150%;
        }

        #sign_penilai {
            text-align: center;
            width: 50%;
        }

        #sign {
            display: flex;
        }

        @media print {

            #sign_penilai,
            blockquote {
                page-break-inside: avoid;
            }
        }

    </style>
</head>

<body>
    <div>
        @php
        $currentYear = $Carbon::now()->format('Y');
        $currentMonth = $Carbon::now()->formatLocalized('%B');
        $currentMonthShort = $Carbon::now()->formatLocalized('%b');
        $today = $Carbon::now()->format('j');
        $firstDay = $date->startOfMonth()->format('j');
        $lastDay = $date->lastOfMonth()->format('j');
        $month = $date->timezone('Asia/Jakarta')->formatLocalized('%B');
        $year = $date->timezone('Asia/Jakarta')->formatLocalized('%Y');

        @endphp
        <div style="display: flex; justify-content: flex-end;">
            <h2 id="reportType">CKP-R</h2>
        </div>
        <h2 id="title">CAPAIAN KINERJA PEGAWAI TAHUN {{ $year }}</h2><br>
        <table style="border: nonek !important; border-collapse: none !important;">
            <tr>
                <td>Satuan Organisasi</td>
                <td>: Badan Pusat Statistik Kota Malang</td>
            </tr>
            <tr>
                <td>Nama / NIP</td>
                <td>: {{auth()->user()->name}} / {{auth()->user()->nip}}</td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>: {{auth()->user()->jabatan}}</td>
            </tr>
            <tr>
                <td>Periode </td>
                <td>: {{$firstDay.' - '.$lastDay.' '.$month.' '.$year}}
                </td>
            </tr>
        </table>
        <br>
    </div>
    <div>
        <table style="width:100%; border-collapse: collapse; text-align: center;" cellpadding="2" border="1">
            <tr>
                <th rowspan="2">No</th>
                <th rowspan="2" width="225px">Uraian Kegiatan</th>
                <th rowspan="2">Satuan</th>
                <th colspan="3">Kuantitas</th>
                <th rowspan="2">Tingkat kualitas (%)</th>
                <th rowspan="2">Kode Butir Kegiatan</th>
                <th rowspan="2">Angka Kredit</th>
                <th rowspan="2" width="25%">Keterangan</th>
            </tr>
            <tr>
                <th style="width:45px">Target</th>
                <th style="width:45px">Reali sasi</th>
                <th style="width:45px">%</th>
            </tr>
            <tr style="font-size:8pt">
                <td>(1)</td>
                <td>(2)</td>
                <td>(3)</td>
                <td>(4)</td>
                <td>(5)</td>
                <td>(6)</td>
                <td>(7)</td>
                <td>(8)</td>
                <td>(9)</td>
                <td>(10)</td>
            </tr>
            @if (!empty($keg_utama))
            <tr>
                <td class="kategori" colspan="10"><b>Utama</b></td>
            </tr>
            @php $counter = 0; @endphp
            @foreach ($keg_utama as $utama)
            @php
            $counter++;
            $ket = null;
            $reali = null;
            $tingkul = null;
            if ($utama->tipe == 'tugas'){
                $ket = json_decode($utama->keterangan_r, true)[auth()->user()->id]["${month}_${year}"];
                $reali = json_decode($utama->realisasi, true)[auth()->user()->id]["${month}_${year}"];
                $tingkul = json_decode($utama->tingkat_kualitas, true)[auth()->user()->id]["${month}_${year}"];
            }else{
                $ket = $utama->keterangan_r;
                $reali = $utama->realisasi;
                $tingkul = $utama->tingkat_kualitas ?? 0;
            }
            @endphp
            <tr>
                <td>{{$counter}}</td>
                <td class="uraian_keg">{{$utama->full_name}}</td>
                <td>{{$utama->satuan}}</td>
                <td>{{$utama->month_volume}}</td>
                <td>{{ $reali }}</td>
                <td>{{ $reali == 0 ? 0 : round($reali/$utama->month_volume*100) }}</td>
                <td>{{ $tingkul }}</td>
                <td>{{$utama->kode_butir}}</td>
                <td>{{$utama->angka_kredit}}</td>
                <td class="keterangan">{{ $ket }}</td>
            </tr>
            @endforeach
            @endif
            @if (!empty($keg_tambahan))
            <tr>
                <td class="kategori" colspan="10"><b>Tambahan</b></td>
            </tr>
            @php $counter = 0; @endphp
            @foreach ($keg_tambahan as $tambahan)
            @php
            $counter++;
            $ket = null;
            $reali = null;
            $tingkul = null;
            if($tambahan->tipe == 'tugas'){
                $ket = json_decode($tambahan->keterangan_r, true)[auth()->user()->id]["${month}_${year}"];
                $reali = json_decode($tambahan->realisasi, true)[auth()->user()->id]["${month}_${year}"];
                $tingkul = json_decode($tambahan->tingkat_kualitas, true)[auth()->user()->id]["${month}_${year}"];
            }else{
                $ket = $tambahan->keterangan_r;
                $reali = $tambahan->realisasi;
                $tingkul = $tambahan->tingkat_kualitas ?? 0;
            }
            @endphp
            <tr>
                <td>{{$counter}}</td>
                <td class="uraian_keg">{{$tambahan->full_name}}</td>
                <td>{{$tambahan->satuan}}</td>
                <td>{{$tambahan->month_volume}}</td>
                <td>{{ $reali }}</td>
                <td>{{ $reali == 0 ? 0: round($reali/$tambahan->month_volume*100)}}</td>
                <td>{{ $tingkul }}</td>
                <td>{{$tambahan->kode_butir}}</td>
                <td>{{$tambahan->angka_kredit}}</td>
                <td class="keterangan">{{$ket}}</td>
            </tr>
            @endforeach
            @endif
        </table>
    </div>
    <div id="sign">
        <div id="sign_dinilai">
            <br>
            <p>Malang, {{$lastDay.' '.$month.' '.$currentYear}} <br>
                Pegawai Yang dinilai <br>
                <br><br><br><br>
                <u>{{auth()->user()->name}}</u><br>
                NIP. {{auth()->user()->nip}}
        </div>
        <div id="sign_penilai">
            <br>
            <p>Pejabat Penilai,<br>
                {{$penilai->jabatan}}
                <br><br><br><br><br>
                <u>{{$penilai->name}}</u><br>
                NIP. {{$penilai->nip}}</p>
        </div>
    </div>
</body>

</html>

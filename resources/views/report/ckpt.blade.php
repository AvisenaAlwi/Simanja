<!DOCTYPE html>
<html lang="en">
@inject('Carbon', '\Carbon\Carbon')

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
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

        #sign_penilai {

            text-align: center;
            margin-left: 70%;
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
        $today = $Carbon::now()->format('d');
        $firstDay = $date->startOfMonth()->format('d');
        $lastDay = $date->startOfMonth()->format('d');
        $month = $date->timezone('Asia/Jakarta')->formatLocalized('%B');
        $year = $date->timezone('Asia/Jakarta')->formatLocalized('%Y');

        @endphp
        <div style="display: flex; justify-content: flex-end;">
            <h2 id="reportType">CKP-T</h2>
        </div>
        <h2 id="title">TARGET KINERJA PEGAWAI TAHUN {{$currentYear}}</h2><br>
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
            <tr style="background: #f3f3f3">
                <th>No</th>
                <th width="45%">Uraian Kegiatan</th>
                <th>Satuan</th>
                <th>Kuantitas</th>
                <th>Kode butir</th>
                <th>Angka kredit</th>
                <th width="30%">Keterangan</th>
            </tr>
            <tr>
                <td>(1)</td>
                <td>(2)</td>
                <td>(3)</td>
                <td>(4)</td>
                <td>(5)</td>
                <td>(6)</td>
                <td>(7)</td>
            </tr>
            @if (!empty($keg_utama))
                <tr style="background: #f3f3f3">
                    <td class="kategori" colspan="7" ><b>Utama</b></td>
                </tr>
                @php $counter = 0; @endphp
                @foreach ($keg_utama as $utama)
                @php $counter++; @endphp
                <tr>
                    <td>{{$counter}}</td>
                    <td class="uraian_keg">{{$utama->full_name}}</td>
                    <td>{{$utama->satuan}}</td>
                    <td>{{$utama->month_volume}}</td>
                    <td>{{$utama->kode_butir}}</td>
                    <td>{{$utama->angka_kredit}}</td>
                    <td class="keterangan">{{$utama->keterangan}}</td>
                </tr>
                @endforeach
            @endif
            @if (!empty($keg_tambahan))
                <tr style="background: #f3f3f3">
                    <td class="kategori" colspan="7"><b>Tambahan</b></td>
                </tr>
                @php $counter = 0; @endphp
                @foreach ($keg_tambahan as $tambahan)
                @php $counter++; @endphp
                <tr>
                    <td>{{$counter}}</td>
                    <td class="uraian_keg">{{$tambahan->full_name}}</td>
                    <td>{{$tambahan->satuan}}</td>
                    <td>{{$tambahan->month_volume}}</td>
                    <td>{{$tambahan->kode_butir}}</td>
                    <td>{{$tambahan->angka_kredit}}</td>
                    <td class="keterangan">{{$tambahan->keterangan}}</td>
                </tr>
                @endforeach
            @endif
        </table>
    </div>
    <div id="sign_penilai">
        <br>
        <p> Malang,{{$today.' '.$currentMonthShort.' '.$currentYear}} <br>
            Pejabat Penilai,<br>
            {{$penilai->jabatan}}
            <br><br><br><br>
            <u>{{$penilai->name}}</u><br>
            NIP. {{$penilai->nip}}</p>
    </div>
</body>

</html>

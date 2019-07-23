<!DOCTYPE html>
<html lang="en">
@inject('Carbon', '\Carbon\Carbon')

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        body {
            font-family: "Arial", Helvetica, sans-serif;
            font-size: 12px;
            margin: 0 5%;
        }

        #title {
            text-align: center;
            margin: 0;
        }

        #reportType {
            text-align: center;
            width: 100px;
            border: 4px solid black;
            margin-left: 80%
        }

        /* table {
            text-align: center;
        } */

        #kategori,
        #uraian_keg,
        #keterangan {
            text-align: left;
        }

        #sign_dinilai {
            text-align: center;
            width: 50%;
        }

        #sign_penilai {
            text-align: center;
            width: 150%;
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
        $today = $Carbon::now()->format('d');
        $firstDay = $Carbon::parse('first day of this month')->format('d');
        $lastDay = $Carbon::parse('last day of this month')->format('d');

        @endphp
        <div style="display: flex; justify-content: flex-end;">
            <h2 id="reportType">CKP-R</h2>
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
                <td>: {{$firstDay.' '.$currentMonth.' '.$currentYear.' - '.$lastDay.' '.$currentMonth.' '.$currentYear}}
                </td>
            </tr>
        </table>
        <br>
    </div>
    <div>
        <table style="width:100%; border-collapse: collapse; text-align: center;" cellpadding="2" border="1">
            <tr>
                <th rowspan="2">No</th>
                <th rowspan="2" width="30%">Uraian Kegiatan</th>
                <th rowspan="2">Satuan</th>
                <th colspan="3">Kuantitas</th>
                <th rowspan="2">Tingkat kualitas</th>
                <th rowspan="2">Kode butir</th>
                <th rowspan="2">Angka kredit</th>
                <th rowspan="2" width="25%">Keterangan</th>
            </tr>
            <tr>
                <th>Target</th>
                <th>Realisasi</th>
                <th>%</th>
            </tr>
            <tr>
                <td>1</td>
                <td>2</td>
                <td>3</td>
                <td>4</td>
                <td></td>
                <td></td>
                <td></td>
                <td>5</td>
                <td>6</td>
                <td>7</td>
            </tr>
            <tr>
                <td id="kategori" colspan="10"><b>Utama</b></td>
            </tr>
            @php $counter = 0; @endphp
            @foreach ($keg_utama as $utama)
            @php $counter++; @endphp
            <tr>
                <td>{{$counter}}</td>
                <td id="uraian_keg">{{$utama->sub_activity_name}}</td>
                <td>{{$utama->satuan}}</td>
                <td>{{$utama->volume}}</td>
                <td></td>
                <td></td>
                <td></td>
                <td>{{$utama->kode_butir}}</td>
                <td>{{$utama->angka_kredit}}</td>
                <td>{{$utama->keterangan}}</td>
            </tr>
            @endforeach

            <tr>
                <td id="kategori" colspan="10"><b>Tambahan</b></td>
            </tr>
            @php $counter = 0; @endphp
            @foreach ($keg_tambahan as $tambahan)
            @php $counter++; @endphp
            <tr>
                <td>{{$counter}}</td>
                <td id="uraian_keg">{{$tambahan->sub_activity_name}}</td>
                <td>{{$tambahan->satuan}}</td>
                <td>{{$tambahan->volume}}</td>
                <td></td>
                <td></td>
                <td></td>
                <td>{{$tambahan->kode_butir}}</td>
                <td>{{$tambahan->angka_kredit}}</td>
                <td>{{$tambahan->keterangan}}</td>
            </tr>
            @endforeach
        </table>
    </div>
    <div id="sign">
        <div id="sign_dinilai">
            <br>
            <p>Malang,{{$today.' '.$currentMonthShort.' '.$currentYear}} <br>
                Pegawai Yang dinilai <br>
                <br><br><br><br>
                {{auth()->user()->name}} <br>
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

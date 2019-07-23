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
        $firstDay = $Carbon::parse('first day of this month')->format('d');
        $lastDay = $Carbon::parse('last day of this month')->format('d');

        @endphp
        <div style="display: flex; justify-content: flex-end;">
            <h2 id="reportType">CKP-T</h2>
        </div>
        <h2 id="title">CAPAIAN KINERJA PEGAWAI TAHUN {{$currentYear}}</h2><br>
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
                <th>No</th>
                <th width="50%">Uraian Kegiatan</th>
                <th>Satuan</th>
                <th>Kuantitas</th>
                <th>Kode butir</th>
                <th>Angka kredit</th>
                <th width="25%">Keterangan</th>
            </tr>
            <tr>
                <td>1</td>
                <td>2</td>
                <td>3</td>
                <td>2</td>
                <td>1</td>
                <td>0</td>
                <td>(1)</td>
            </tr>
            <tr>
                <td id="kategori" colspan="7"><b>Utama</b></td>
            </tr>
            @php $counter = 0; @endphp
            @foreach ($keg_utama as $utama)
            @php $counter++; @endphp
            <tr>
                <td>{{$counter}}</td>
                <td id="uraian_keg">{{$utama->sub_activity_name}}</td>
                <td>{{$utama->satuan}}</td>
                <td>{{$utama->volume}}</td>
                <td>{{$utama->kode_butir}}</td>
                <td>{{$utama->angka_kredit}}</td>
                <td>{{$utama->keterangan}}</td>
            </tr>
            @endforeach

            <tr>
                <td id="kategori" colspan="7"><b>Tambahan</b></td>
            </tr>
            @php $counter = 0; @endphp
            @foreach ($keg_tambahan as $tambahan)
            @php $counter++; @endphp
            <tr>
                <td>{{$counter}}</td>
                <td id="uraian_keg">{{$tambahan->sub_activity_name}}</td>
                <td>{{$tambahan->satuan}}</td>
                <td>{{$tambahan->volume}}</td>
                <td>{{$tambahan->kode_butir}}</td>
                <td>{{$tambahan->angka_kredit}}</td>
                <td>{{$tambahan->keterangan}}</td>
            </tr>
            @endforeach
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

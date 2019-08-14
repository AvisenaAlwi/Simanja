@inject('Carbon', '\Carbon\Carbon')
@inject('User', 'App\User')
@inject('Activity', '\App\Activity')
@extends('layouts.app', ['title' => $my_activity->my_activity_name])

@push('style')
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" type="text/css" href="{{ asset('vendor/bootstrapslider') }}/bootstrap-slider.min.css" />
<style>
    .slider.slider-horizontal {
        margin-left: 7%;
        margin-bottom: 10px !important;
        font-size: 13px;
    }

</style>
@endpush


@section('content')
@include('users.partials.header', [
'title' => 'Detail kegiatan',
// 'description' => 'halaman berikut menunjukkan Detail kegiatan yang yang telah dibuat.'
])

<div class="container-fluid mt--7">
    <div class="row">
        <div class="col-xl-12 order-xl-1">
            <div class="card bg-secondary shadow">
                <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="text-muted">Kegiatanku</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ redirect()->getUrlGenerator()->previous() }}"
                                    title="Kembali" data-toggle="tooltip" data-placement="top">
                                    <button type="button"
                                        class="btn btn-primary btn-sm"><span class="ni ni-bold-left"></span>
                                    </button>
                                </a>
                            </div>
                        </div>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h1 class="display-4 text-success">{{$my_activity->my_activity_name}}</h1>
                        </div>
                        <div class="col-6 text-right">
                            <h5>Dibuat :
                                {{ $Carbon::createFromTimeStamp(strtotime($my_activity->created_at))->diffForHumans() }}
                            </h5>
                        </div>
                    </div>
                        <h4>dibuat oleh: {{$my_activity->user_name}}</h4>
                        @if (date_create($my_activity->awal)->format("Y-m") == date_create($my_activity->akhir)->format("Y-m"))
                        <h4>Periode: {{ $Carbon::parse($my_activity->awal)->formatLocalized('%B %Y') }}
                        @else
                        <h4>Periode: {{ $Carbon::parse($my_activity->awal)->formatLocalized('%B %Y') . ' - ' .
                            $Carbon::parse($my_activity->akhir)->formatLocalized('%B %Y')}}</h4>
                        @endif
                        <h4>Kategori: {{$my_activity->kategori}}
                        <h4>Satuan: {{$my_activity->satuan}} | Volume: {{$my_activity->volume}} | Kode Butir Kegiatan : {{ $my_activity->kode_butir != null ? $my_activity->kode_butir : ' -' }}
                            | Angka Kredit : {{$my_activity->angka_kredit != null ? $my_activity->kode_butir : ' -'}}</h4>
                            <h4>Keterangan :</h4>
                            <h5>{{$my_activity->keterangan_t != null ? $my_activity->keterangan_t : ' -'}}</h5>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.footers.auth')
</div>



@endsection

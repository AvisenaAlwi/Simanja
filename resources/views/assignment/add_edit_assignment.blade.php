@extends('layouts.app')
@push('style')
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="{{ asset('vendor/easyautocomplete') }}/easy-autocomplete.min.css">
<link rel="stylesheet" type="text/css" href="{{ asset('vendor/bootstrapslider') }}/bootstrap-slider.min.css" />
<style>
    .slider.slider-horizontal{
        margin-left: 7%;
        margin-bottom: 10px !important;
        font-size: 13px;
    }
</style>
@endpush

@php
$bulan_now = (int)now()->format('m');
$tahun_now = (int)now()->format('Y');
$tahun = [];
for($i = 0; $i < 5; $i++){
    array_push($tahun, $tahun_now++);
}
@endphp

@section('content')
@include('users.partials.header', [
'title' => 'Penugasan',
'description' => __('Halaman ini bertujuan menambahkan kegiatan sekaligus sub-kegiatan. Minimal terdapat satu sub kegiatan dalam 1 kegiatan.'),
'class' => 'col-lg-7'
])
<div class="container-fluid mt--7">
        <div class="row">
        <div class="col-xl-12 order-xl-1">
            <div class="card bg-secondary shadow">
                <div class="card-header bg-white border-0">
                    <div class="row align-items-center">
                        <h3 class="col-12 mb-0">
                            Tugaskan <strong class="text-success">{{ $assignment->name . " " . $assignment->activity->name }}</strong>
                        </h3>
                    </div>
                </div>
                <div class="card-body">
                    <h3>Kualifikasi yang dibutuhkan</h3>
                    <table>
                        <tr>
                            <td>Pendidikan (min.)</td>
                            <td>{{ $assignment->pendidikan }}</td>
                        </tr>
                        <tr>
                            <td>Kemempuan TI</td>
                            <td>{{ $assignment->ti }}</td>
                        </tr>
                        <tr>
                            <td>Kemampuan Menulis</td>
                            <td>{{ $assignment->menulis }}</td>
                        </tr>
                        <tr>
                            <td>Kemampuan Administrasi</td>
                            <td>{{ $assignment->administrasi }}</td>
                        </tr>
                        <tr>
                            <td>Pengalaman Survei</td>
                            <td>{{ $assignment->pengalaman_survei }}</td>
                        </tr>
                    </table>
                    <br>
                    <br>
                    @if (isset($recomended_employee))
                        <span>Berdasarkan kualifikasi diatas kami <b>menyarankan</b> pegawai berikut untuk mengemban tugas ini</span>
                        <br>
                        <br>
                        @foreach ($recomended_employee as $employee)
                            {{ $employee->name }}
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>

    @include('layouts.footers.auth')
    @endsection
    @push('js')
    <!-- Latest compiled and minified JavaScript -->
    <script type="text/javascript" src="{{ asset('vendor/arrive') }}/arrive.min.js"></script>
    <script type="text/javascript" src="{{ asset('vendor/easyautocomplete') }}/jquery.easy-autocomplete.min.js"></script>
    <script type="text/javascript" src="{{ asset('vendor/momentjs') }}/moment.min.js"></script>
    <script type="text/javascript" src="{{ asset('vendor/bootstrapslider') }}/bootstrap-slider.min.js"></script>
    <script>
        var value = [];

        function saveValue() {
            let arr_nama_kegiatan = $('.nama-sub-kegiatan');
            let arr_satuan_kegiatan = $('.satuan-sub-kegiatan');
            let arr_volume_kegiatan = $('.volume-sub-kegiatan');
            let arr_qualifikasi_pendidikan = $('.qualifikasi-pendidikan');
            let arr_qualifikasi_ti = $('.qualifikasi-ti');
            let arr_qualifikasi_menulis = $('.qualifikasi-menulis');
            let arr_qualifikasi_administrasi = $('.qualifikasi-administrasi');
            let arr_qualifikasi_pengalaman = $('.qualifikasi-pengalaman');

            for (let i = 0; i < arr_nama_kegiatan.length; i++) {
                zzz = {};
                zzz.nama_sub_kegiatan = $(arr_nama_kegiatan[i]).val();
                zzz.satuan_sub_kegiatan = $(arr_satuan_kegiatan[i]).val();
                zzz.volume_sub_kegiatan = $(arr_volume_kegiatan[i]).val();
                zzz.qualifikasi = {
                    pendidikan : $(arr_qualifikasi_pendidikan[i]).val(),
                    ti : $(arr_qualifikasi_ti[i]).val(),
                    menulis : $(arr_qualifikasi_menulis[i]).val(),
                    administrasi : $(arr_qualifikasi_administrasi[i]).val(),
                    pengalaman : $(arr_qualifikasi_pengalaman[i]).val(),
                }
                value.push(zzz);
            }
        }

        function restoreValue() {
            let arr_nama_kegiatan = $('.nama-sub-kegiatan');
            let arr_satuan_kegiatan = $('.satuan-sub-kegiatan');
            let arr_volume_kegiatan = $('.volume-sub-kegiatan');
            let arr_qualifikasi_pendidikan = $('.qualifikasi-pendidikan');
            let arr_qualifikasi_ti = $('.qualifikasi-ti');
            let arr_qualifikasi_menulis = $('.qualifikasi-menulis');
            let arr_qualifikasi_administrasi = $('.qualifikasi-administrasi');
            let arr_qualifikasi_pengalaman = $('.qualifikasi-pengalaman');
            for (let i = 0; i < value.length; i++) {
                $(arr_nama_kegiatan[i]).val(value[i].nama_sub_kegiatan);
                $(arr_satuan_kegiatan[i]).val(value[i].satuan_sub_kegiatan);
                $(arr_volume_kegiatan[i]).val(value[i].volume_sub_kegiatan);
                
                $(arr_qualifikasi_pendidikan[i]).val(value[i].qualifikasi.pendidikan);
                $(arr_qualifikasi_ti[i]).val(value[i].qualifikasi.ti);
                $(arr_qualifikasi_menulis[i]).val(value[i].qualifikasi.menulis);
                $(arr_qualifikasi_administrasi[i]).val(value[i].qualifikasi.administrasi);
                $(arr_qualifikasi_pengalaman[i]).val(value[i].qualifikasi.pengalaman);
                
                $(arr_qualifikasi_pendidikan[i]).slider({ value : value[i].qualifikasi.pendidikan});
                $(arr_qualifikasi_ti[i]).slider({ value : value[i].qualifikasi.ti});
                $(arr_qualifikasi_menulis[i]).slider({ value : value[i].qualifikasi.menulis});
                $(arr_qualifikasi_administrasi[i]).slider({ value : value[i].qualifikasi.administrasi});
                $(arr_qualifikasi_pengalaman[i]).slider({ value : value[i].qualifikasi.pengalaman});
            }
            value = [];
        }
        $(document).ready(function () {
            let sub_activity_count = 1;
            var contoh_sub_activity = $('.sub-kegiatan');
            $('#add-sub-activity').click(function () {
                $('#container-sub-activity .slider.slider-horizontal').remove();
                let before = $('#container-sub-activity').html();
                saveValue();
                $('#container-sub-activity').html('');
                sub_activity_count += 1;
                let html = `
                <div class="sub-kegiatan" number="1">
                                <hr class="my-4" />
                                <div class="row">
                                    <div class="col-6"><h6 class="heading-small text-muted mb-4"><b>Sub Kegiatan 1</b></h6></div>
                                    <div class="col-6 d-flex justify-content-end" >
                                        <h1 class="delete-sub-activity" style="cursor: pointer;">
                                            <i aria-hidden="true" class="fa fa-times text-danger"></i>
                                        </h1>
                                    </div>
                                </div>

                                <div class="pl-lg-4">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-control-label" for="input-current-password">Nama Sub Kegiatan 1</label>
                                                <input type="text" name="sub_activity_1_name" class="form-control form-control-alternative nama-sub-kegiatan" value="" required>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-control-label" for="input-password">Satuan Sub Kegiatan 1</label>
                                                <input type="text" name="sub_activity_1_satuan" class="form-control form-control-alternative satuan-sub-kegiatan" value="" required>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-control-label" for="input-password">Volume Sub Kegiatan 1</label>
                                                <input type="number" name="sub_activity_1_volume" class="form-control form-control-alternative volume-sub-kegiatan" value="" required>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    
                                                </div>
                                                <div class="col-lg-6">
                                                    
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6" style="border-left:1px solid #ccc;">
                                            <h3>Kualifikasi Yang Dibutuhkan</h3>
                                            <div class="form-group" style="margin-left:10px">
                                                <div class="form-control-label" for="input-current-password">Minimal Pendidikan</div>
                                                <input type="text" name="q_sub_activity_1_pendidikan" class="qualifikasi-pendidikan"
                                                    data-provide="slider"
                                                    data-slider-ticks="[5,4,3,2,1]"
                                                    data-slider-ticks-labels='["S2", "S1", "D-IV", "D-III","SMA"]'
                                                    data-slider-min="1"
                                                    data-slider-max="5"
                                                    data-slider-step="1"
                                                    data-slider-value="2"
                                                    data-slider-tooltip="hide"
                                                    style="width: 86%;" />
                                            </div>

                                            <div class="form-group" style="margin-left:10px">
                                                <div class="form-control-label" for="input-current-password">Kemampuan TI</div>
                                                <input type="text" name="q_sub_activity_1_ti" class="qualifikasi-ti"
                                                    data-provide="slider"
                                                    data-slider-ticks="[5,4,3,2,1]"
                                                    data-slider-ticks-labels='["Sangat Tinggi", "Tinggi", "Cukup", "Kurang","Sangat Kurang"]'
                                                    data-slider-min="1"
                                                    data-slider-max="5"
                                                    data-slider-step="1"
                                                    data-slider-value="2"
                                                    data-slider-tooltip="hide"
                                                    style="width: 86%;" />
                                            </div>
                                            <div class="form-group" style="margin-left:10px">
                                                <div class="form-control-label" for="input-current-password">Kemampuan Menulis</div>
                                                <input type="text" name="q_sub_activity_1_menulis" class="qualifikasi-menulis"
                                                    data-provide="slider"
                                                    data-slider-ticks="[5,4,3,2,1]"
                                                    data-slider-ticks-labels='["Sangat Tinggi", "Tinggi", "Cukup", "Kurang","Sangat Kurang"]'
                                                    data-slider-min="1"
                                                    data-slider-max="5"
                                                    data-slider-step="1"
                                                    data-slider-value="2"
                                                    data-slider-tooltip="hide"
                                                    style="width: 86%;" />
                                            </div>
                                            <div class="form-group" style="margin-left:10px">
                                                <div class="form-control-label" for="input-current-password">Kemampuan Administrasi</div>
                                                <input type="text" name="q_sub_activity_1_administrasi" class="qualifikasi-administrasi"
                                                    data-provide="slider"
                                                    data-slider-ticks="[5,4,3,2,1]"
                                                    data-slider-ticks-labels='["Sangat Tinggi", "Tinggi", "Cukup", "Kurang","Sangat Kurang"]'
                                                    data-slider-min="1"
                                                    data-slider-max="5"
                                                    data-slider-step="1"
                                                    data-slider-value="2"
                                                    data-slider-tooltip="hide"
                                                    style="width: 86%;" />
                                            </div>
                                            <div class="form-group" style="margin-left:10px">
                                                <div class="form-control-label" for="input-current-password">Pengalaman Survei</div>
                                                <input type="text" name="q_sub_activity_1_pengalaman" class="qualifikasi-pengalaman"
                                                    data-provide="slider"
                                                    data-slider-ticks="[5,4,3,2,1]"
                                                    data-slider-ticks-labels='["Sangat Tinggi", "Tinggi", "Cukup", "Kurang","Sangat Kurang"]'
                                                    data-slider-min="1"
                                                    data-slider-max="5"
                                                    data-slider-step="1"
                                                    data-slider-value="2"
                                                    data-slider-tooltip="hide"
                                                    style="width: 86%;" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                `
                html = html.replace(/number="\d+"/g, `number="` + sub_activity_count + `"`);
                html = html.replace(/Sub Kegiatan\s*\d+/g, "Sub Kegiatan " + sub_activity_count);
                html = html.replace(/sub_activity_\d+/g, "sub_activity_" + sub_activity_count);
                before += html;
                $('#container-sub-activity').append(before);
                $('.delete-sub-activity').hide();
                $('.delete-sub-activity').last().show();
                
                setTimeout(function(){
                    $('html, body').animate({
                        scrollTop: $('.sub-kegiatan').last().offset().top
                    }, 1000);
                }, 10);
                restoreValue();
            });

            $('#form-activity').submit(function (e) {
                // e.preventDefault();
                // let me = $(this);
                // console.log(me.serialize());
            });
            $("#input-activity-name").easyAutocomplete({
                url: "{{ route('activity.autocomplete.activity') }}",
                getValue: "name",
                list: {
                    maxNumberOfElements: 15,
                    match: {enabled: true},
                    sort: {enabled: true}
                },
            });
            $('.nama-sub-kegiatan').each(function () {
                $(this).easyAutocomplete({
                    url: "{{ route('activity.autocomplete.subactivity') }}",
                    getValue: 'name',
                    list: {
                        maxNumberOfElements: 15,
                        match: {enabled: true},
                        sort: {enabled: true}
                    }
                });
            });
            $('.satuan-sub-kegiatan').each(function () {
                $(this).easyAutocomplete({
                    url: "{{ route('activity.autocomplete.satuan') }}",
                    getValue: 'name',
                    list: {
                        maxNumberOfElements: 15,
                        match: {enabled: true},
                        sort: {enabled: true}
                    }
                });
            });

            $('#checkbox-periode-1-bulan').change(function(){
                let me = $(this);
                if(me.is(":checked")){
                    $('#zzx').hide();
                    me.val(true);
                }else{
                    $('#zzx').show();
                    me.val(false);
                }
            });

            $(document).arrive('.delete-sub-activity', function(newElement){
            $(newElement).click(function(){
                me = $(this);

                me.parent().parent().parent().hide(function(){
                    $(this).remove();
                    $('.delete-sub-activity').hide();
                    $('.delete-sub-activity').last().show();
                    sub_activity_count--;
                });
            })
        });

        });

        $(document).arrive('.nama-sub-kegiatan', function (newElement) {
            let zzz = $('.nama-sub-kegiatan');
            $('.nama-sub-kegiatan').unbind('easyAutocomplete');
            zzz.each(function () {
                $(this).easyAutocomplete({
                    url: "{{ route('activity.autocomplete.subactivity') }}",
                    getValue: 'name',
                    list: {
                        maxNumberOfElements: 15,
                        match: {enabled: true},
                        sort: {enabled: true}
                    }
                });
            });
        });
        $(document).arrive('.satuan-sub-kegiatan', function (newElement) {
            let zzz = $('.satuan-sub-kegiatan');
            $('.satuan-sub-kegiatan').unbind('easyAutocomplete');
            zzz.each(function () {
                $(this).easyAutocomplete({
                    url: "{{ route('activity.autocomplete.satuan') }}",
                    getValue: 'name',
                    list: {
                        maxNumberOfElements: 15,
                        match: {enabled: true},
                        sort: {enabled: true}
                    }
                });
            });
        });
        $(document).arrive('input[data-provide=slider]', function(newElement){
            $(newElement).slider();
        });

    </script>
    @endpush

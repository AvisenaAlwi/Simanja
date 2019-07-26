@extends('layouts.app', ['title' => 'Tambah kegiatan'])
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
'title' => 'Kegiatan'
])
<div class="container-fluid mt--7">
        <div class="row">
        <div class="col-xl-12 order-xl-1">
            <div class="card bg-secondary shadow">
                <div class="card-header bg-white border-0">
                    <div class="row align-items-center">
                        <h3 class="col-12 mb-0">Tambah Kegiatan</h3>
                    </div>
                </div>
                <div class="card-body">
                    <form id="form-activity" method="POST" action="{{ route('activity.store') }}">
                        @csrf
                        @method('post')
                        <h6 class="heading-small text-muted mb-4">{{ __('Kegiatan') }}</h6>

                        @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('status') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        @endif

                        <div class="pl-lg-4">
                            <div class ="row">
                                <div class="col-lg-6">
                                    <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-activity-name">Nama Kegiatan</label>
                                    <input type="text" name="activity_name" id="input-activity-name" class="form-control form-control-alternative{{ $errors->has('activity_name') ? ' is-invalid' : '' }}" value="{{ old('activity_name') }}" required autofocus>

                                    @if ($errors->has('activity_name'))
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('activity_name') }}</strong>
                                    </span>
                                    @endif
                                    </div>
                                </div>
                                <div class ="col-lg-6">
                                    <div class="form-group{{ $errors->has('kategori') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-kategori">Kategori</label>
                                    <select class="form-control form-control-alternative{{ $errors->has('activity_kategori') ? ' is-invalid' : '' }}" id="input-kategori" name="activity_kategori" required autofocus>
                                        <option value="Utama">Utama</option>
                                        <option value="Tambahan">Tambahan</option>
                                    </select>
                                    @if ($errors->has('activity_kategori'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('activity_kategori') }}</strong>
                                    </span>
                                    @endif
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group{{ $errors->has('kategori') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="input-activity-start-month">Awal</label>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <select name="activity_start_month" id="activity-start-month"
                                                class="form-control form-control-alternative{{ $errors->has('activity_name') ? ' is-invalid' : '' }}" value="{{ old('activity_value') }}" required>
                                                    @for ($i = 1; $i <= sizeof(config('scale.month')); $i++)
                                                        @if ($i == $bulan_now)
                                                            <option value="{{ config('scale.month')[$i-1] }}" selected>{{ config('scale.month')[$i-1] }}</option>
                                                        @else
                                                            <option value="{{ config('scale.month')[$i-1] }}">{{ config('scale.month')[$i-1] }}</option>
                                                        @endif
                                                    @endfor
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <select name="activity_start_year" id="activity-start-year"
                                                class="form-control form-control-alternative{{ $errors->has('activity_name') ? ' is-invalid' : '' }}" value="{{ old('activity_value') }}" required>
                                                    @foreach ($tahun as $t)
                                                        <option value="{{ $t }}">{{ $t }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6" id="zzx">
                                    <div class="form-group{{ $errors->has('kategori') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="input-activity-end-month">Akhir</label>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <select name="activity_end_month" id="activity-end-month"
                                                class="form-control form-control-alternative{{ $errors->has('activity_name') ? ' is-invalid' : '' }}" value="{{ old('activity_value') }}" required>
                                                @for ($i = 1; $i <= sizeof(config('scale.month')); $i++)
                                                    @if ($i == $bulan_now)
                                                        <option value="{{ config('scale.month')[$i-1] }}" selected>{{ config('scale.month')[$i-1] }}</option>
                                                    @else
                                                        <option value="{{ config('scale.month')[$i-1] }}">{{ config('scale.month')[$i-1] }}</option>
                                                    @endif
                                                @endfor
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <select name="activity_end_year" id="activity-end-year"
                                                class="form-control form-control-alternative{{ $errors->has('activity_name') ? ' is-invalid' : '' }}" value="{{ old('activity_value') }}" required>
                                                    @foreach ($tahun as $t)
                                                        <option value="{{ $t }}">{{ $t }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        @if ($errors->has('activity_end_month'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('activity_end_month') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div>
                                <input type="checkbox" id="checkbox-periode-1-bulan" name="issatubulan"> Periode Tugas 1 Bulan
                            </div>
                        </div>
                        <div id="container-sub-activity">
                            <div class="sub-kegiatan" number="1">
                                <hr class="my-4" />
                                <div class="col-md-6"><h6 class="heading-small text-muted mb-4"><b>Sub Kegiatan 1</b></h6></div>

                                {{-- @if (session('sub_activity'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('sub_activity') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                @endif --}}

                                <div class="pl-lg-4">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-control-label" for="input-current-password">Nama Sub Kegiatan 1</label>
                                                <input type="text" name="sub_activity_1_name" class="form-control form-control-alternative nama-sub-kegiatan" value="" required>
                                            </div>
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label class="form-control-label" for="input-password">Satuan Sub Kegiatan 1</label>
                                                        <input type="text" name="sub_activity_1_satuan" class="form-control form-control-alternative satuan-sub-kegiatan" value="" required>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label class="form-control-label" for="input-password">Volume Sub Kegiatan 1</label>
                                                        <input type="number" name="sub_activity_1_volume" class="form-control form-control-alternative volume-sub-kegiatan" value="" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <label class="form-control-label">Kode Butir Sub Kegiatan 1</label>
                                                    <input type="text" name="sub_activity_1_kode_butir" class="form-control form-control-alternative" value="">
                                                </div>
                                                <div class="col-lg-6">
                                                    <label class="form-control-label">Angka Kredit Sub Kegiatan 1</label>
                                                    <input type="text" name="sub_activity_1_angka_kredit" class="form-control form-control-alternative" value="">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-control-label">Keterangan Sub Kegiatan 1</label>
                                                <textarea name="sub_activity_1_keterangan" id="" rows="2" class="form-control form-control-alternative"></textarea>
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
                        </div>
                        <hr class="my-4" />
                        <div class="row">
                            <div class="col-md-6">
                                <button type="button" id="add-sub-activity" class="btn btn-warning">Tambah Sub Kegiatan</button>
                            </div>
                            <div class="col-md-6 d-flex justify-content-end">
                                <button type="submit" class="btn btn-success" id="btn-submit">Simpan</button>
                            </div>
                        </div>
                        {{-- <div class="text-center">
                            
                        </div> --}}
                    </form>
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
                    pendidikan: $(arr_qualifikasi_pendidikan[i]).val(),
                    ti: $(arr_qualifikasi_ti[i]).val(),
                    menulis: $(arr_qualifikasi_menulis[i]).val(),
                    administrasi: $(arr_qualifikasi_administrasi[i]).val(),
                    pengalaman: $(arr_qualifikasi_pengalaman[i]).val(),
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

                $(arr_qualifikasi_pendidikan[i]).slider({ value: value[i].qualifikasi.pendidikan });
                $(arr_qualifikasi_ti[i]).slider({value: value[i].qualifikasi.ti });
                $(arr_qualifikasi_menulis[i]).slider({ value: value[i].qualifikasi.menulis });
                $(arr_qualifikasi_administrasi[i]).slider({ value: value[i].qualifikasi.administrasi });
                $(arr_qualifikasi_pengalaman[i]).slider({ value: value[i].qualifikasi.pengalaman });
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
                                    <div class="col-md-6"><h6 class="heading-small text-muted mb-4"><b>Sub Kegiatan 1</b></h6></div>
                                    <div class="col-md-6 d-flex justify-content-end" >
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
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label class="form-control-label" for="input-password">Satuan Sub Kegiatan 1</label>
                                                        <input type="text" name="sub_activity_1_satuan" class="form-control form-control-alternative satuan-sub-kegiatan" value="" required>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label class="form-control-label" for="input-password">Volume Sub Kegiatan 1</label>
                                                        <input type="number" name="sub_activity_1_volume" class="form-control form-control-alternative volume-sub-kegiatan" value="" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <label class="form-control-label">Kode Butir Sub Kegiatan 1</label>
                                                    <input type="number" name="sub_activity_1_kode_butir" class="form-control form-control-alternative" value="">
                                                </div>
                                                <div class="col-lg-6">
                                                    <label class="form-control-label">Angka Kredit Sub Kegiatan 1</label>
                                                    <input type="number" name="sub_activity_1_angka_kredit" class="form-control form-control-alternative" value="">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-control-label" for="input-password">Keterangan Sub Kegiatan 1</label>
                                                <textarea name="sub_activity_1_keterangan" id="" rows="2" class="form-control form-control-alternative"></textarea>
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

                setTimeout(function () {
                    $('html, body').animate({
                        scrollTop: $('.sub-kegiatan').last().offset().top
                    }, 1000);
                }, 10);
                restoreValue();
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
                        match: {
                            enabled: true
                        },
                        sort: {
                            enabled: true
                        }
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

            $('#checkbox-periode-1-bulan').change(function() {
                let me = $(this);
                if (me.is(":checked")) {
                    $('#zzx').hide();
                    me.val(true);
                    $('label[for=input-activity-start-month]').html("Awal - Akhir")
                } else {
                    $('#zzx').show();
                    me.val(false);
                    $('label[for=input-activity-start-month]').html("Awal")
                }
            });
            $('#activity-start-month').change(function () {
                let selectedIndex = this.selectedIndex;
                let endMonthChildernOption = $('#activity-end-month').children();
                for (let i = 0; i < endMonthChildernOption.length; i++) {
                    $(endMonthChildernOption[i]).show();
                }
                for (let i = 0; i < selectedIndex; i++) {
                    $(endMonthChildernOption[i]).hide();
                }
                if (selectedIndex > $('#activity-end-month')[0].selectedIndex) {
                    for (let i = 0; i < endMonthChildernOption.length; i++) {
                        $(endMonthChildernOption[i]).removeAttr('selected');
                    }
                    $(endMonthChildernOption[selectedIndex]).attr('selected', true);
                }
            });
            $('#activity-start-month').trigger('change');
            $('#activity-start-year').change(function () {
                let selectedIndex = this.selectedIndex;
                let endYearChildernOption = $('#activity-end-year').children();
                for (let i = 0; i < endYearChildernOption.length; i++) {
                    $(endYearChildernOption[i]).show();
                }
                for (let i = 0; i < selectedIndex; i++) {
                    $(endYearChildernOption[i]).hide();
                }
                if (selectedIndex > $('#activity-end-year')[0].selectedIndex) {
                    for (let i = 0; i < endYearChildernOption.length; i++) {
                        $(endYearChildernOption[i]).removeAttr('selected');
                    }
                    $(endYearChildernOption[selectedIndex]).attr('selected', true);
                }
            });
            $('#activity-end-year').change(function () {
                let selectedIndex = this.selectedIndex;
                let endMonthChildernOption = $('#activity-end-month').children();
                if (selectedIndex > $('#activity-start-year')[0].selectedIndex) {
                    for (let i = 0; i < endMonthChildernOption.length; i++) {
                        $(endMonthChildernOption[i]).show();
                    }
                }
            });

            $(document).arrive('.delete-sub-activity', function (newElement) {
                $(newElement).click(function () {
                    me = $(this);

                    me.parent().parent().parent().hide(function () {
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
        $(document).arrive('input[data-provide=slider]', function(newElement) {
            $(newElement).slider();
        });

    </script>
    @endpush

@inject('Cookie', 'Illuminate\Support\Facades\Cookie')
@extends('layouts.app', ['title' => __('User Management')])
@push('style')
<!-- Latest compiled and minified CSS -->
@if ($Cookie::get('dark') == 'true')
<link rel="stylesheet" href="{{ asset('vendor/easyautocomplete') }}/dark-easy-autocomplete.min.css">
@else
<link rel="stylesheet" href="{{ asset('vendor/easyautocomplete') }}/easy-autocomplete.min.css">
@endif
<link rel="stylesheet" type="text/css" href="{{ asset('vendor/bootstrapslider') }}/bootstrap-slider.min.css" />
<link rel="stylesheet" type="text/css" href="{{ asset('vendor/croppie') }}/croppie.min.css" />
<style>
    .slider.slider-horizontal {
        margin-left: 7%;
        margin-bottom: 10px !important;
        font-size: 13px;
    }

</style>
@endpush

@section('content')
@include('users.partials.header', ['title' => 'Manajemen Akun'])

<div class="container-fluid mt--7">
    <div class="row">
        <div class="col-xl-12 order-xl-1">
            <div class="card bg-secondary shadow">
                <div class="card-header bg-white border-0">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0">Tambah Pengguna</h3>
                        </div>
                        <div class="col-4 text-right">
                            <a href="{{ route('user.index') }}" title="Kembali" data-toggle="tooltip"
                                data-placement="top">
                                <button type="button" class="btn btn-primary btn-sm"><span
                                        class="ni ni-bold-left"></span>
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
                <form method="post" action="{{ route('user.store') }}" autocomplete="off" enctype="multipart/form-data">
                    <div class="card-body">
                        @csrf
                        @php
                        @endphp
                        <h6 class="heading-small text-muted mb-4">Infomrasi Pengguna</h6>
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-name">Nama</label>
                                    <input type="text" name="name" id="input-name"
                                        class="form-control form-control-alternative{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                        placeholder="{{ __('Name') }}" value="{{ old('name') }}" required autofocus>

                                    @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-group{{ $errors->has('nip') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-account-nip">NIP</label>
                                    <input type="text" name="nip" id="input-account-nip"
                                        class="form-control form-control-alternative{{ $errors->has('nip') ? ' is-invalid' : '' }}"
                                        value="{{ old('nip') }}" required autofocus>
    
                                    @if ($errors->has('nip'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('nip') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group{{ $errors->has('password') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-password">{{ __('Password') }}</label>
                                    <input type="password" name="password" id="input-password"
                                        class="form-control form-control-alternative{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                        placeholder="{{ __('Password') }}" value="" required
                                        autocomplete="false">

                                    @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label"
                                        for="input-password-confirmation">Konfirmasi Password</label>
                                    <input type="password" name="password_confirmation" id="input-password-confirmation"
                                        class="form-control form-control-alternative" autocomplete="false"
                                        placeholder="{{ __('Confirm Password') }}" value="" required>
                                    @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                    @endif
                                </div>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">

                                <div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-account-email">E-mail</label>
                                    <input type="email" name="email" id="input-account-email"
                                        class="form-control form-control-alternative{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                        value="{{ old('email') }}" required autofocus>

                                    @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>

                                <div class="form-group{{ $errors->has('pejabat_penilai_nip') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-account-penilai_nip">Pejabat
                                        penilai NIP</label>
                                    <input type="text" name="pejabat_penilai_nip" id="input-account-penilai_nip"
                                        class="form-control form-control-alternative{{ $errors->has('pejabat_penilai_nip') ? ' is-invalid' : '' }}"
                                        value="{{ old('pejabat_penilai_nip') }}" required autofocus>

                                    @if ($errors->has('pejabat_penilai_nip'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('pejabat_penilai_nip') }}</strong>
                                    </span>
                                    @endif
                                </div>

                                <div class="form-group{{ $errors->has('role_id') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-role_id">Level akun</label>
                                    <select
                                        class="form-control form-control-alternative{{ $errors->has('role_id') ? ' is-invalid' : '' }}"
                                        id="input-role_id" name="role_id" required autofocus>
                                        <option value="3">Pegawai</option>
                                        <option value="2">Supervisor</option>
                                        <option value="1">Admin</option>
                                    </select>
                                    @if ($errors->has('role_id'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('role_id') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class='row'>
                                    <div class="col-lg-6 form-group{{ $errors->has('jabatan') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="input-jabatan">Jabatan*</label>
                                        <input type="text"
                                            class="form-control form-control-alternative{{ $errors->has('jabatan') ? ' is-invalid' : '' }}"
                                            id="input-jabatan" name="jabatan" value="{{ old('jabatan') }}" required autofocus>
                                        @if ($errors->has('jabatan'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('jabatan') }}</strong>
                                        </span>
                                        @endif
                                    </div>

                                    <div
                                        class="col-lg-6 form-group{{ $errors->has('photo_base64') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="input-photo">Foto*</label>
                                        <input type="file"
                                            class="form-control form-control-alternative{{ $errors->has('photo_base64') ? ' is-invalid' : '' }}"
                                            id="input-photo" name="photo" value="" autofocus accept="image/png, image/jpeg"> Max 2 MB
                                        @if ($errors->has('photo_base64'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('photo_base64') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6" style="border-left:1px solid #ccc;">
                                <h3>Tingkat keahlian</h3>
                                <div class="form-group" style="margin-left:10px">
                                    <div class="form-control-label">Pendidikan</div>
                                    <input type="text" name="pendidikan" class="qualifikasi-pendidikan"
                                        data-provide="slider" data-slider-ticks="[5,4,3,2,1]"
                                        data-slider-ticks-labels='["S2", "S1", "D-IV", "D-III","SMA"]'
                                        data-slider-min="1" data-slider-max="5" data-slider-step="1"
                                        data-slider-value="2" data-slider-tooltip="hide" style="width: 86%;" />
                                </div>

                                <div class="form-group" style="margin-left:10px">
                                    <div class="form-control-label">Kemampuan TI
                                    </div>
                                    <input type="text" name="ti" class="qualifikasi-ti" data-provide="slider"
                                        data-slider-ticks="[5,4,3,2,1]"
                                        data-slider-ticks-labels='["Sangat Tinggi", "Tinggi", "Cukup", "Kurang","Sangat Kurang"]'
                                        data-slider-min="1" data-slider-max="5" data-slider-step="1"
                                        data-slider-value="2" data-slider-tooltip="hide" style="width: 86%;" />
                                </div>
                                <div class="form-group" style="margin-left:10px">
                                    <div class="form-control-label">Kemampuan
                                        Menulis</div>
                                    <input type="text" name="menulis" class="qualifikasi-menulis"
                                        data-provide="slider" data-slider-ticks="[5,4,3,2,1]"
                                        data-slider-ticks-labels='["Sangat Tinggi", "Tinggi", "Cukup", "Kurang","Sangat Kurang"]'
                                        data-slider-min="1" data-slider-max="5" data-slider-step="1"
                                        data-slider-value="2" data-slider-tooltip="hide" style="width: 86%;" />
                                </div>
                                <div class="form-group" style="margin-left:10px">
                                    <div class="form-control-label">Kemampuan
                                        Administrasi</div>
                                    <input type="text" name="administrasi" class="qualifikasi-administrasi"
                                        data-provide="slider" data-slider-ticks="[5,4,3,2,1]"
                                        data-slider-ticks-labels='["Sangat Tinggi", "Tinggi", "Cukup", "Kurang","Sangat Kurang"]'
                                        data-slider-min="1" data-slider-max="5" data-slider-step="1"
                                        data-slider-value="2" data-slider-tooltip="hide" style="width: 86%;" />
                                </div>
                                <div class="form-group" style="margin-left:10px">
                                    <div class="form-control-label">Pengalaman
                                        Survei</div>
                                    <input type="text" name="pengalaman_survei" class="qualifikasi-pengalaman"
                                        data-provide="slider" data-slider-ticks="[5,4,3,2,1]"
                                        data-slider-ticks-labels='["Sangat Tinggi", "Tinggi", "Cukup", "Kurang","Sangat Kurang"]'
                                        data-slider-min="1" data-slider-max="5" data-slider-step="1"
                                        data-slider-value="2" data-slider-tooltip="hide" style="width: 86%;" />
                                </div>

                            </div>
                        </div>
                        <br>
                        <div class="upload-demo-wrap">
                            <div id="upload-demo"></div>
                        </div>
                        <input type="hidden" name="photo_base64">
                        <hr class="my-4" />
                        <div class="row">
                            <div class="col-7 d-flex justify-content-end">
                                <button type="submit" class="btn btn-success mt-4">Simpan</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @include('layouts.footers.auth')
</div>
@push('js')
<!-- Latest compiled and minified JavaScript -->
<script src="{{ asset('vendor/arrive') }}/arrive.min.js"></script>
<script src="{{ asset('vendor/easyautocomplete') }}/jquery.easy-autocomplete.min.js"></script>
<script type="text/javascript" src="{{ asset('vendor/bootstrapslider') }}/bootstrap-slider.min.js"></script>
<script type="text/javascript" src="{{ asset('vendor/croppie') }}/croppie.min.js"></script>
<script>
    // $(document).arrive('.slider-input', function (newElement) {
    //     $('.slider-input[identifier=pendidikan]').jRange({
    //         from: 1,
    //         to: 5,
    //         scale: ['S2','S1','D-IV','D-III','SMA'],
    //         width: 400,
    //         snap: true,
    //         showLabels: false
    //     });
    //     $(`.slider-input[identifier=ti],
    //     .slider-input[identifier=menulis],
    //     .slider-input[identifier=administrasi],
    //     .slider-input[identifier=pengalaman]`).jRange({
    //         from: 1,
    //         to: 5,
    //         scale: ['Sangat Kurang','Kurang','Cukup','Tinggi','Sangat Tinggi'],
    //         width: 400,
    //         snap: true,
    //         showLabels: false
    //     });
    // });
    $(document).arrive('input[data-provide=slider]', function (newElement) {
        $(newElement).slider();
    });

    $("#input-jabatan").easyAutocomplete({
        url: "{{ route('user.autocomplete.jabatan') }}",
        getValue: "name",
        list: {
            maxNumberOfElements: 15,
            match: {
                enabled: true
            },
            sort: {
                enabled: true
            }
        },
    });
    $("input[name='pengalaman_survei']").slider({ min: 1, max: 5, value: 2, focus: true });

    $('#upload-demo').hide();
    var resize = $('#upload-demo').croppie({
        enableExif: true,
        enableOrientation: true,
        viewport: { // Default { width: 100, height: 100, type: 'square' }
            width: 400,
            height: 400
        },
        boundary: {
            width: 450,
            height: 450
        }
    });
    $('#input-photo').on('change', function () {
        $('#upload-demo').show();
        var reader = new FileReader();
        reader.onload = function (e) {
            resize.croppie('bind', {
                url: e.target.result
            }).then(function () {

            });
        }
        reader.readAsDataURL(this.files[0]);
    });
    $('form').submit(function(){
        resize.croppie('result', {
            type: 'canvas',
            size: 'viewport'
        }).then(function (img) {
            $('input[name="photo_base64"]').val(img);
        });
    });
</script>
@endpush

@endsection

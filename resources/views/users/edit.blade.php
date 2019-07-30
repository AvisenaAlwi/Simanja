@extends('layouts.app', ['title' => 'Manajemen Akun'])


@push('style')
<!-- Latest compiled and minified CSS -->

<link rel="stylesheet" href="{{ asset('vendor/easyautocomplete') }}/easy-autocomplete.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link rel="stylesheet" type="text/css"
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/10.6.1/css/bootstrap-slider.css" />
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
                            <h3 class="mb-0">Edit Akun</h3>
                        </div>
                        <div class="col-4 text-right">
                            <a href="{{ route('user.index') }}" class="btn btn-sm btn-primary">Kembali</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('user.update', $user) }}" autocomplete="off">
                        @csrf
                        @method('put')

                        <h6 class="heading-small text-muted mb-4">Informasi Pengguna</h6>
                        <div class="pl-lg-4">
                            <div class="row">
                                <div class="col-12 col-sm-6">
                                    <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="input-name">Nama*</label>
                                        <input type="text" name="name" id="input-name"
                                            class="form-control form-control-alternative{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                            placeholder="{{ __('Name') }}" value="{{ old('name', $user->name) }}"
                                            required autofocus>

                                        @if ($errors->has('name'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div class="form-group{{ $errors->has('nip') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="input-nip">NIP*</label>
                                        <input type="text" name="nip" id="input-nip"
                                            class="form-control form-control-alternative{{ $errors->has('nip') ? ' is-invalid' : '' }}"
                                            placeholder="{{ __('nip') }}" value="{{ old('nip', $user->nip) }}" required>

                                        @if ($errors->has('nip'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('nip') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-16 col-sm-6">
                                    <div class="form-group{{ $errors->has('password') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="input-password">Password *</label>
                                        <input type="password" name="password" id="input-password"
                                            class="form-control form-control-alternative{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                            placeholder="{{ __('Password') }}" value="">

                                        @if ($errors->has('password'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-password-confirmation">Konfirmasi
                                            Password</label>
                                        <input type="password" name="password_confirmation"
                                            id="input-password-confirmation"
                                            class="form-control form-control-alternative"
                                            placeholder="{{ __('Confirm Password') }}" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">

                                    <div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="input-account-email">E-mail*</label>
                                        <input type="email" name="email" id="input-account-email"
                                            class="form-control form-control-alternative{{ $errors->has('account_email') ? ' is-invalid' : '' }}"
                                            value="{{ old('email', $user->email) }}" required autofocus>

                                        @if ($errors->has('account_nip'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('account_email') }}</strong>
                                        </span>
                                        @endif
                                    </div>

                                    <div class="form-group{{ $errors->has('penilai_nip') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="input-account-penilai_nip">Pejabat
                                            penilai NIP*</label>
                                        <input type="text" name="pejabat_penilai_nip" id="input-account-penilai_nip"
                                            class="form-control form-control-alternative{{ $errors->has('account_penilai_nip') ? ' is-invalid' : '' }}"
                                            value="{{ old('pejabat_penilai_nip', $user->pejabat_penilai_nip) }}"
                                            required autofocus>

                                        @if ($errors->has('account_penilai_nip'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('account_penilai_nip') }}</strong>
                                        </span>
                                        @endif
                                    </div>

                                    <div class="form-group{{ $errors->has('role_id') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="input-role_id">Level akun*</label>
                                        <select
                                            class="form-control form-control-alternative{{ $errors->has('account_role_id') ? ' is-invalid' : '' }}"
                                            id="input-role_id" name="role_id"
                                            value="{{ old('role_id', $user->role_id) }}" required autofocus>
                                            <option value="3" {{ $user->role_id == "3" ? 'selected' : ''}}>Pegawai
                                            </option>
                                            <option value="2" {{ $user->role_id == "2" ? 'selected' : ''}}>
                                                Supervisor</option>
                                            <option value="1" {{ $user->role_id == "1" ? 'selected' : ''}}>Admin
                                            </option>
                                        </select>
                                        @if ($errors->has('account_role_id'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('account_role_id') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <div class='row'>
                                        <div
                                            class="col-lg-6 form-group{{ $errors->has('jabatan') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="input-jabatan">Jabatan*</label>
                                            <input type="text"
                                                class="form-control form-control-alternative{{ $errors->has('account_jabatan') ? ' is-invalid' : '' }}"
                                                id="input-jabatan" name="jabatan" value="{{ old('jabatan', $user->jabatan) }}" required autofocus>
                                            @if ($errors->has('account_jabatan'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('account_jabatan') }}</strong>
                                            </span>
                                            @endif
                                        </div>

                                        <div
                                            class="col-lg-6 form-group{{ $errors->has('photo') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="input-photo">Foto*</label>
                                            <input type="file"
                                                class="form-control form-control-alternative{{ $errors->has('account_photo') ? ' is-invalid' : '' }}"
                                                id="input-photo" name="photo" value="" autofocus>
                                            @if ($errors->has('account_photo'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('account_photo') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                </div>
                            </div>
                            <div class="col-lg-6" style="border-left:1px solid #ccc;">
                                <h3>Tingkat keahlian</h3>
                                <div class="form-group" style="margin-left:10px">

                                    @php
                                    if ($user->pendidikan=="S2") {
                                    $pendidikan = "1";
                                    }elseif ($user->pendidikan=="S1") {
                                    $pendidikan = "2";
                                    }elseif ($user->pendidikan=="D-IV") {
                                    $pendidikan = "3";
                                    }elseif ($user->pendidikan=="D-III") {
                                    $pendidikan = "4";
                                    }elseif ($user->pendidikan=="SMA") {
                                    $pendidikan = "5";
                                    }

                                    @endphp
                                    <div class="form-control-label" for="input-current-password">Pendidikan</div>
                                    <input type="text" name="pendidikan" class="qualifikasi-pendidikan"
                                        data-provide="slider" data-slider-ticks="[5,4,3,2,1]"
                                        data-slider-ticks-labels='["S2", "S1", "D-IV", "D-III","SMA"]'
                                        data-slider-min="1" data-slider-max="5" data-slider-step="1"
                                        data-slider-value="{{ $pendidikan }}" data-slider-tooltip="hide"
                                        style="width: 86%;" />
                                </div>

                                <div class="form-group" style="margin-left:10px">

                                    @php
                                    if ($user->ti=="Sangat Tinggi") {
                                    $ti = "1";
                                    }elseif ($user->ti=="Tinggi") {
                                    $ti = "2";
                                    }elseif ($user->ti=="Cukup") {
                                    $ti = "3";
                                    }elseif ($user->ti=="Kurang") {
                                    $ti = "4";
                                    }elseif ($user->ti=="Sangat Kurang") {
                                    $ti = "5";
                                    }

                                    @endphp
                                    <div class="form-control-label" for="input-current-password">Kemampuan TI
                                    </div>
                                    <input type="text" name="ti" class="qualifikasi-ti" data-provide="slider"
                                        data-slider-ticks="[5,4,3,2,1]"
                                        data-slider-ticks-labels='["Sangat Tinggi", "Tinggi", "Cukup", "Kurang","Sangat Kurang"]'
                                        data-slider-min="1" data-slider-max="5" data-slider-step="1"
                                        data-slider-value="{{ $ti }}" data-slider-tooltip="hide" style="width: 86%;" />
                                </div>
                                <div class="form-group" style="margin-left:10px">

                                    @php
                                    if ($user->menulis=="Sangat Tinggi") {
                                    $menulis = "1";
                                    }elseif ($user->menulis=="Tinggi") {
                                    $menulis = "2";
                                    }elseif ($user->menulis=="Cukup") {
                                    $menulis = "3";
                                    }elseif ($user->menulis=="Kurang") {
                                    $menulis = "4";
                                    }elseif ($user->menulis=="Sangat Kurang") {
                                    $menulis = "5";
                                    }

                                    @endphp
                                    <div class="form-control-label" for="input-current-password">Kemampuan
                                        Menulis</div>
                                    <input type="text" name="menulis" class="qualifikasi-menulis" data-provide="slider"
                                        data-slider-ticks="[5,4,3,2,1]"
                                        data-slider-ticks-labels='["Sangat Tinggi", "Tinggi", "Cukup", "Kurang","Sangat Kurang"]'
                                        data-slider-min="1" data-slider-max="5" data-slider-step="1"
                                        data-slider-value="{{ $menulis }}" data-slider-tooltip="hide"
                                        style="width: 86%;" />
                                </div>
                                <div class="form-group" style="margin-left:10px">

                                    @php
                                    if ($user->administrasi=="Sangat Tinggi") {
                                    $administrasi = "1";
                                    }elseif ($user->administrasi=="Tinggi") {
                                    $administrasi = "2";
                                    }elseif ($user->administrasi=="Cukup") {
                                    $administrasi = "3";
                                    }elseif ($user->administrasi=="Kurang") {
                                    $administrasi = "4";
                                    }elseif ($user->administrasi=="Sangat Kurang") {
                                    $administrasi = "5";
                                    }

                                    @endphp
                                    <div class="form-control-label" for="input-current-password">Kemampuan
                                        Administrasi</div>
                                    <input type="text" name="administrasi" class="qualifikasi-administrasi"
                                        data-provide="slider" data-slider-ticks="[5,4,3,2,1]"
                                        data-slider-ticks-labels='["Sangat Tinggi", "Tinggi", "Cukup", "Kurang","Sangat Kurang"]'
                                        data-slider-min="1" data-slider-max="5" data-slider-step="1"
                                        data-slider-value="{{ $administrasi }}" data-slider-tooltip="hide"
                                        style="width: 86%;" />
                                </div>
                                <div class="form-group" style="margin-left:10px">

                                    @php
                                    if ($user->pengalaman_survei=="Sangat Tinggi") {
                                    $pengalaman_survei = "1";
                                    }elseif ($user->pengalaman_survei=="Tinggi") {
                                    $pengalaman_survei = "2";
                                    }elseif ($user->pengalaman_survei=="Cukup") {
                                    $pengalaman_survei = "3";
                                    }elseif ($user->pengalaman_survei=="Kurang") {
                                    $pengalaman_survei = "4";
                                    }elseif ($user->pengalaman_survei=="Sangat Kurang") {
                                    $pengalaman_survei = "5";
                                    }

                                    @endphp
                                    <div class="form-control-label" for="input-current-password">Pengalaman
                                        Survei</div>
                                    <input type="text" name="pengalaman_survei" class="qualifikasi-pengalaman"
                                        data-provide="slider" data-slider-ticks="[5,4,3,2,1]"
                                        data-slider-ticks-labels='["Sangat Tinggi", "Tinggi", "Cukup", "Kurang","Sangat Kurang"]'
                                        data-slider-min="1" data-slider-max="5" data-slider-step="1"
                                        data-slider-value="{{ $pengalaman_survei }}" data-slider-tooltip="hide"
                                        style="width: 86%;" />
                                </div>

                            </div>
                        </div>
                        <p>* Biarkan jika tidak ingin mengubah.</p>
                </div>
                <div class="row">
                    <div class="col-12 d-flex justify-content-center">
                        <button type="submit" class="btn btn-success mt-4">Simpan</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
@include('layouts.footers.auth')
</div>
@push('js')
<!-- Latest compiled and minified JavaScript -->

<script src="{{ asset('vendor/arrive') }}/arrive.min.js"></script>
<script src="{{ asset('vendor/easyautocomplete') }}/jquery.easy-autocomplete.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script type="text/javascript"
    src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/10.6.1/bootstrap-slider.min.js"></script>
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

</script>
@endpush

@endsection

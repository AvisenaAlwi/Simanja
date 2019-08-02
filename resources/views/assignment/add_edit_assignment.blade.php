@extends('layouts.app')
@push('style')
<!-- Latest compiled and minified CSS -->
<style>
    table, th, td {
       border: 1px solid black;
       border-collapse: collapse;
       padding: .5rem
    }
    input[type="number"]{
        border: none;
        background: transparent;
        border-bottom: 1px solid #888;
        width: 70px;
        border-radius: 0;
    }
    input[type="number"]:focus{
        outline: none;
    }

    .md-chip {
    display: inline-block;
    background: #e0e0e0;
    padding: 0 12px;
    border-radius: 32px;
    font-size: 13px;
    transition: all .1s ease-in-out
    }
    .md-chip.md-chip-hover:hover {
    background: #ccc;
    }

    .md-chip-clickable {
    cursor: pointer;
    }

    .md-chip,
    .md-chip-icon {
    height: 40px;
    line-height: 40px;
    font-size: 14px;
    font-weight: 600;
    color: black
    }

    .md-chip-icon {
    display: block;
    float: left;
    background: #009587;
    width: 40px;
    border-radius: 50%;
    text-align: center;
    color: white;
    margin: 0 8px 0 -12px;
    }

    .md-chip-remove {
    display: inline-block;
    background: #aaa;
    border: 0;
    height: 20px;
    width: 20px;
    border-radius: 50%;
    padding: 0;
    margin: 0 -4px 0 4px;
    cursor: pointer;
    font: inherit;
    line-height: 20px;
    }
    .md-chip-remove:after {
    color: #e0e0e0;
    content: "x";
    display: block;
    text-align: center;
    }
    .md-chip-remove:hover {
    background: #999;
    }
    .md-chip-remove:active {
    background: #777;
    }

    .md-chips {
    padding: 12px 0;
    }
    .md-chips .md-chip {
    margin: 0 5px 3px 0;
    }

    .md-chip-raised {
    box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.14), 0 1px 5px 0 rgba(0, 0, 0, 0.12), 0 3px 1px -2px rgba(0, 0, 0, 0.2);
    }
    .md-chip-hover.active:hover{
        background: #6ad98d;
        color: #fff;
    }
    .md-chip-clickable.active{
        background: #7bed9f;
        color: #000;
    }
    .md-chip-hover.disabled:hover{
        background: #f3f3f3;
        color: #bebebe;
    }
    .md-chip-clickable.disabled{
        background: #f3f3f3;
        color: #bebebe;
        cursor:not-allowed;
    }
    .disabled .md-chip-icon{
        filter: grayscale(70%);
        -webkit-filter: grayscale(70%);
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
$petugas = json_decode($assignment->petugas, true);
@endphp

@section('content')
@include('users.partials.header', [
'title' => 'Penugasan',
'description' => 'Halaman ini bertujuan menambahkan kegiatan sekaligus sub-kegiatan. Minimal terdapat satu sub kegiatan dalam 1 kegiatan.'
])
<div class="container-fluid mt--7">
    <form action="{{ route('assignment.update', $assignment->id) }}" method="post" id="form-assignment">
        @csrf
        @method('put')
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <h3 class="col-12 mb-0">
                                Tugaskan <strong class="text-success">{{ $assignment->subActivity->name . " " . $assignment->activity->name }}</strong>
                            </h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <h3>Kualifikasi yang dibutuhkan</h3>
                        <table class="table table-sm table-dark">
                                <thead>
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Keahlian</th>
                                        <th scope="col">Tingkat</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row">1</th>
                                        <td>Pendidikan (min.)</td>
                                        <td>{{$subActivity->pendidikan}}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">2</th>
                                        <td>TI</td>
                                        <td>{{$subActivity->ti}}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">3</th>
                                        <td>Menulis</td>
                                        <td>{{$subActivity->menulis}}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">4</th>
                                        <td>Administrasi</td>
                                        <td>{{$subActivity->administrasi}}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">5</th>
                                        <td>Pengalaman survei</td>
                                        <td>{{$subActivity->pengalaman_survei}}</td>
                                    </tr>
                                </tbody>
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
                        @for($i = 0; $i < sizeof($users); $i+=2)
                            @php 
                            $user = $users[$i];
                            if ($i + 1 < sizeof($users))
                                $user2 = $users[$i+1]; 
                            @endphp
                            <div class="row">
                                <div class="col-12 col-lg-4">
                                    <label>
                                        @php $isDisabled = auth()->user()->role_id != 1 && $user->jabatan === 'Kepala' @endphp
                                        <input class="user d-none" type="checkbox" name="{{ $user->name }}" value="{{ $user->id }}"
                                        {{ $isDisabled ? 'disabled' : '' }} {{ array_key_exists($user->id, $petugas) ? 'checked' : '' }}/>
                                        <div class="md-chip md-chip-hover md-chip-clickable {{ $isDisabled ? 'disabled' : '' }} {{ array_key_exists($user->id, $petugas) ? 'active' : '' }}">
                                            <img src="{{ asset('storage') }}/{{ $user->photo }}" alt="" class="md-chip-icon">
                                            {{ $user->name }}
                                            <div class="md-chip-remove"></div>
                                        </div>
                                    </label>
                                </div>
                                @if ($i + 1 < sizeof($users))
                                <div class="col-12 col-lg-8">
                                    <label>
                                        @php $isDisabled = auth()->user()->role_id != 1 && $user2->jabatan === 'Kepala' @endphp
                                        <input class="user d-none" type="checkbox" name="{{ $user2->name }}" value="{{ $user2->id }}"
                                        {{ $isDisabled ? 'disabled' : '' }} {{ array_key_exists($user2->id, $petugas) ? 'checked' : '' }}/>
                                        <div class="md-chip md-chip-hover md-chip-clickable {{ $isDisabled ? 'disabled' : '' }} {{ array_key_exists($user2->id, $petugas) ? 'active' : '' }}">
                                            <img src="{{ asset('storage') }}/{{ $user2->photo }}" alt="" class="md-chip-icon">
                                            {{ $user2->name }}
                                            <div class="md-chip-remove"></div>
                                        </div>
                                    </label>
                                </div>
                                @endif
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-xl-12 order-xl-1">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <h3 class="col-12 mb-0">
                                Pengalokasian
                            </h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="table-user" class="table-sm table-dark" style="text-align: center">
                                <tr>
                                    <th width="300px" rowspan="2">Nama</th>
                                    <th colspan="{{ sizeof($months) }}">Bulan</th>
                                </tr>
                                <tr>
                                    @foreach ($months as $month)
                                        <th>{{ $month['monthName'] }}</th>
                                    @endforeach
                                </tr>
                                @forelse ($petugas as $idPetugas => $monthsAssign)
                                    <tr class="user-row" data-user-id="{{ $idPetugas }}">
                                        <td class="text-left">{{ $users_name[$idPetugas] }}</td>
                                        @foreach($monthsAssign as $month => $monthValue)
                                            <td><input type="number" name="{{ $idPetugas }}_{{ $month }}" value="{{ $monthValue }}" class="form-control" min="0"></td>
                                        @endforeach
                                    </tr>
                                @empty
                                    
                                @endforelse
                            </table>
                            Total: <b id="total"></b> dari <b>{{ $subActivity->volume }}</b>
                        </div>
                        <button class="mx-auto w-auto p-3 m-2 btn btn-warning btn-block text-center" type="button" id="btn-submit-edit">
                            <i class="ni ni-single-copy-04"></i> Tugaskan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    @include('layouts.footers.auth')
</div>
    @endsection
    @push('js')
    <!-- Latest compiled and minified JavaScript -->
    <script type="text/javascript" src="{{ asset('vendor/arrive') }}/arrive.min.js"></script>
    <script type="text/javascript" src="{{ asset('vendor/sweetalert2') }}/sweetalert2.min.js"></script>
    <script>
        $('.md-chip:not(.active) .md-chip-remove').hide()
        $(document).ready(function(){
            let maxVolume = {{ $subActivity->volume }}
            function calculateTotalAllocation(){
                let total = 0;
                $('input[type="number"]').each(function(index){
                    total += parseInt($(this).val() == '' ? '0' : $(this).val())
                });
                $('#total').html(total);
                return total;
            }
            function inputalokasi(){
                var value = $(this).val();
                let maxValue = calculateTotalAllocation() - value;
                console.log(maxValue);
                if (value == ''){
                    value = 0;
                }
                if ((value !== '') && (value.indexOf('.') === -1)) {
                    $(this).val(Math.max(Math.min(value, maxValue), 0));
                }
                $(this).val(value)
                let totalValueNow = calculateTotalAllocation();
                if (totalValueNow > maxVolume){
                    $(this).addClass('is-invalid')
                    swal.fire("Berlebihan", 'Total alokasi melebihi volume kegiatan', 'error')
                    .then((result) => {
                    })
                }else
                    $(this).removeClass('is-invalid');
                calculateTotalAllocation();
            }

            let exampleRow = `
<tr class="user-row" data-user-id="[[ id ]]">
    <td class="text-left">[[ nama ]]</td>
    @foreach($months as $month)
    <td><input type="number" name="[[ key ]]_{{ $month['monthName'] }}" value="0" class="form-control input-alokasi" min="0"></td>
    @endforeach
</tr>
            `
            $("input[type='checkbox'].user").change(function(){
                let me = $(this)
                let userId = me.val()
                let userName = me.attr('name')
                let tableUser = $('#table-user')
                if (me[0].checked){
                    let xample = new String(exampleRow);
                    xample = xample.replace(/\[\[ id \]\]/g, userId)
                    xample = xample.replace(/\[\[ nama \]\]/g, userName)
                    xample = xample.replace(/\[\[ key \]\]/g, userId)
                    tableUser.append(xample)
                }else{
                    $("tr.user-row[data-user-id='"+userId+"']").remove();
                }
                calculateTotalAllocation();
            });
            $(document).arrive('input[type="number"]', function() {
                let newElem = $(this);
                newElem.bind('input', inputalokasi);
            });
            calculateTotalAllocation()
            $('.md-chip.md-chip-hover.md-chip-clickable').click(function(){
                let me = $(this);
                let checkboxBefore = me.prev();
                let removeButton = $(me.children()[me.children.length-1]);
                if (checkboxBefore.attr('disabled'))
                    return;
                if (checkboxBefore[0].checked){
                    me.removeClass('active')
                    removeButton.hide()
                    checkboxBefore.checked = true
                }else{
                    me.addClass('active')
                    removeButton.show()
                    checkboxBefore.checked = false
                }
                calculateTotalAllocation()
            });
            $(window).keydown(function(event){
                if(event.keyCode == 13) {
                event.preventDefault();
                return false;
                }
            });
            $('input.input-alokasi').on('input', inputalokasi);
            $('#btn-submit-edit').click(function(){
                let maxValue = {{ (int)$subActivity->volume }};
                let val = calculateTotalAllocation();
                if (val > maxValue){
                    swal.fire("Berlebihan", 'Total alokasi melebihi volume kegiatan', 'error')
                }else{
                    swal.fire({
                        title: 'Peringatan',
                        html: 'Mengupdate penugasan dapat mereset realisasi, tingkat kualitas, dan keterangan pada CKP-R untuk kegiatan ini. Ingin lanjutkan?',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, lanjutkan',
                        cancelButtonText: 'Batal'
                    })
                    .then((result) => {
                        if(result.value){
                            $('#form-assignment').submit();
                        }
                    });
                }
            })
        });
    </script>
    @endpush

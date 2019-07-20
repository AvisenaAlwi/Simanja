@extends('layouts.app')
@push('style')
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="{{ asset('vendor/easyautocomplete') }}/easy-autocomplete.min.css">
<link rel="stylesheet" type="text/css" href="{{ asset('vendor/bootstrapslider') }}/bootstrap-slider.min.css" />
<link rel="stylesheet" type="text/css" href="{{ asset('vendor/tagify') }}/tagify.css" />
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
'description' => 'Halaman ini bertujuan menambahkan kegiatan sekaligus sub-kegiatan. Minimal terdapat satu sub kegiatan dalam 1 kegiatan.'
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

                    <input name='petugas' placeholder='Ketik nama pegawai'>
                </div>
            </div>
        </div>
    </div>

    @include('layouts.footers.auth')
    @endsection
    @push('js')
    <!-- Latest compiled and minified JavaScript -->
    <script type="text/javascript" src="{{ asset('vendor/arrive') }}/arrive.min.js"></script>
    <script type="text/javascript" src="{{ asset('vendor/tagify') }}/jQuery.tagify.min.js"></script>
    <script>
        $(document).ready(function(){
            var $input = $('input[name=petugas]').tagify(
                {
                    delimiters : null,
                    whitelist: [
                        {value: "Pegawai Tidak Direkomendasikan 1", recomended: false,},
                        {value: "Pegawai Tidak Direkomendasikan 2", recomended: true}
                    ],
                    dropdown: {
                        enabled: 1,
                        classname: "extra-properties"
                    },
                    enforceWhitelist: true,
                    templates : {
                        tag : function(v, tagData){
                            try{
                            return `<tag title='${v}' contenteditable='false' spellcheck="false" class='tagify__tag ${tagData.class ? tagData.class : ""}' ${this.getAttributes(tagData)}>
                                            <x title='remove tag' class='tagify__tag__removeBtn'></x>
                                            <div>
                                                ${tagData.recomended ?
                                                `<img onerror="this.style.visibility = 'hidden'" src='https://lipis.github.io/flag-icon-css/flags/4x3/id.svg'>` : ''
                                                }
                                                <span class='tagify__tag-text'>${v}</span>
                                            </div>
                                        </tag>`;
                            }
                            catch(err){}
                        },

                        dropdownItem : function(tagData){
                            try{
                            return `<div class='tagify__dropdown__item ${tagData.class ? tagData.class : ""}'>
                                            <img onerror="this.style.visibility = 'hidden'"
                                                src='https://lipis.github.io/flag-icon-css/flags/4x3/id.svg'>
                                            <span>${tagData.value}</span>
                                        </div>`
                            }
                            catch(err){}
                        }
                    }
                }
            );
            tagify = $input.data('tagify');
            tagify.on("dropdown:show", onSuggestionsListUpdate)
                .on("dropdown:hide", onSuggestionsListHide)

            renderSuggestionsList()

            // ES2015 argument destructuring
            function onSuggestionsListUpdate({
                detail: suggestionsElm
            }) {
                console.log(suggestionsElm)
            }

            function onSuggestionsListHide() {
                console.log("hide dropdown")
            }

            // https://developer.mozilla.org/en-US/docs/Web/API/Element/insertAdjacentElement
            function renderSuggestionsList() {
                tagify.dropdown.show.call(tagify) // load the list
                tagify.DOM.scope.parentNode.appendChild(tagify.DOM.dropdown)
            }
        });
    </script>
    @endpush

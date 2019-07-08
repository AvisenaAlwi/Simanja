@extends('layouts.app')
@section('content')
@include('users.partials.header', [
'title' => 'Kegiatan',
'description' => __('Tabel berikut menunjukkan tabel kegiatan yang dapat ditambah ke sistem.'),
'class' => 'col-lg-7'
])
@endsection
@extends('errors.illustrated-layout')

@section('code', '419')
@section('title', 'Halaman kadaluarsa')

@section('image')
    <div style="background-image: url({{ asset('/svg/403.svg') }});" class="absolute pin bg-cover bg-no-repeat md:bg-left lg:bg-center">
    </div>
@endsection

@section('message', $exception->getMessage() ?: 'Halaman telah kadaluarsa, refresh halaman dan coba lagi')
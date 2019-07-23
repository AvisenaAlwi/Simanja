@extends('errors.illustrated-layout')

@section('code', '403')
@section('title', 'Tidak diizinkan')

@section('image')
    <div style="background-image: url({{ asset('/svg/403.svg') }});" class="absolute pin bg-cover bg-no-repeat md:bg-left lg:bg-center">
    </div>
@endsection

@section('message', $exception->getMessage() ?: 'Maaf, Anda tidak diizinkan mengakses halaman ini')
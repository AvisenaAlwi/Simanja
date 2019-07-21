@extends('errors.illustrated-layout')

@section('code', '500')
@section('title', 'Error')

@section('image')
    <div style="background-image: url({{ asset('/svg/500.svg') }});" class="absolute pin bg-cover bg-no-repeat md:bg-left lg:bg-center">
    </div>
@endsection

@section('message', $exception->getMessage() ?: 'Aduh, terjadi kesalahan pada server')

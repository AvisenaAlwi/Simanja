@extends('errors.illustrated-layout')

@section('code', '503')
@section('title', 'Layanan tidak ditemukan')

@section('image')
    <div style="background-image: url({{ asset('/svg/503.svg') }});" class="absolute pin bg-cover bg-no-repeat md:bg-left lg:bg-center">
    </div>
@endsection

@section('message', $exception->getMessage() ?: 'Maaf, website dalam masa perbaikan harap menunggu')

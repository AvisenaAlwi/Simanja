@extends('errors::illustrated-layout')

@section('code', '404')
@section('title', __('Page Not Found'))

@section('image')
    <div style="background-image: url({{ asset('/svg/404.svg') }});" class="absolute pin bg-cover bg-no-repeat md:bg-left lg:bg-center">
    </div>
@endsection

{{-- @section('message', empty($exception->getMessage()) ? "Halaman tidak ditemukan" : $exception->getMessage()) --}}
@section('message', $exception->getMessage() ?: "Halaman tidak ditemukan")


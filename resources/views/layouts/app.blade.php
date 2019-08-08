@inject('Cookie', 'Illuminate\Support\Facades\Cookie')
@php
    if (!isset($showSearch))
        $showSearch = false;
    $routeName = Route::getCurrentRoute()->getName();
    $activeSideBar = $routeName == null || strpos($routeName, '.') === false ? $routeName : explode('.', $routeName)[0] ;
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ isset($title) ? $title .' | ' : '' }}{{ config('app.name', 'Simanja') }}</title>
        <!-- Favicon -->
        <link href="{{ asset('img/simanja favicon 16x16.png') }}"  rel="icon" type="image/png">
        {{-- <link href="{{ asset('argon') }}/img/brand/favicon.png" rel="icon" type="image/png"> --}}
        <!-- Fonts -->
        {{-- <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet"> --}}
        <link href="{{ asset('vendor/googlefonts/opensans-family.css') }}" rel="stylesheet">
        <!-- Icons -->
        <link href="{{ asset('argon') }}/vendor/nucleo/css/nucleo.css" rel="stylesheet">
        <link href="{{ asset('argon') }}/vendor/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
        <!-- Argon CSS -->
        @if ($Cookie::get('dark') == 'true')
        <link type="text/css" href="{{ asset('argon') }}/css/argon-dark.min.css?v=1.0.0" rel="stylesheet">
        @else
        <link type="text/css" href="{{ asset('argon') }}/css/argon.min.css?v=1.0.0" rel="stylesheet">
        @endif
        @stack('style')
    </head>
    <body class="{{ $class ?? '' }}">
        @auth()
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
            @include('layouts.navbars.sidebar', ['selectedMenu' => $selectedMenu ?? 'dashboard', 'activeSidebar' => $activeSideBar])
        @endauth

        <div class="main-content">
            @include('layouts.navbars.navbar', ['showSearch' => false])
            @yield('content')
        </div>

        @guest()
            @include('layouts.footers.guest')
        @endguest

        <script src="{{ asset('argon') }}/vendor/jquery/dist/jquery.min.js"></script>
        <script src="{{ asset('argon') }}/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
        <script src="{{ asset('vendor/jquerycookie') }}/jquery.cookie.js"></script>
        <script src="{{ asset('vendor/bootstraptoggle') }}/bootstrap-toggle.min.js"></script>
        <script>
            $('input[data-toggle=toggle]').bootstrapToggle({
                on: 'Tema Gelap',
                off: 'Tema Terang'
            });
            $('input[data-toggle=toggle]').change((e)=>{
                x = $(e.target).prop('checked');
                t = $(document.getElementsByTagName('head')[0].children[9]);
                var links = window.document.getElementsByTagName('link');
                if (x){
                    t.attr('href', '{{ asset('argon') }}/css/argon-dark.css?v=1.0.0');
                    $(links).each(function() {
                        let link = $(this).attr('href');
                        if ( /.*easy-autocomplete.min.css$/.test(link)){
                            $(this).attr('href', '{{ asset('vendor/easyautocomplete') }}/dark-easy-autocomplete.min.css')
                        }
                    });
                    $.cookie('dark', true, { path: '/' });
                }else{
                    t.attr('href', '{{ asset('argon') }}/css/argon.css?v=1.0.0')
                    $(links).each(function() {
                        let link = $(this).attr('href');
                        if ( /.*easy-autocomplete.min.css$/.test(link)){
                            $(this).attr('href', '{{ asset('vendor/easyautocomplete') }}/easy-autocomplete.min.css')
                        }
                    });
                    $.cookie('dark', false, { path: '/' });
                }
            });
        </script>
        @stack('js')
        <!-- Argon JS -->
        <script src="{{ asset('argon') }}/js/argon.js?v=1.0.0"></script>
    </body>
</html>

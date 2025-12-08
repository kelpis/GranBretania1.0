{{--
    Layout base: app.blade.php
    Propósito: layout sencillo para páginas de sesión/guest (login, register), carga de Vite y favicons.
--}}

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

    {{-- Script tema oscuro: respeta sistema + recuerda preferencia --}}
    <script>
        (function () {
            const userTheme = localStorage.getItem('theme');
            const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

            if (userTheme === 'dark' || (!userTheme && systemPrefersDark)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>

    {{-- Inclusión de favicons --}}
    @include('partials.favicons')

    {{-- Fuentes --}}
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    {{-- Scripts --}}
    <!-- Scripts -->
    @if(config('services.recaptcha.site'))
        <script>window.recaptchaSiteKey = "{{ config('services.recaptcha.site') }}";</script>
    @endif
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/recaptcha.js'])
    </head>


    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            {{-- Inclusión de navegación según rol --}}
            @if(auth()->check() && auth()->user()->is_admin)
                @include('layouts.navigationAdmin')
            @else
                @include('layouts.navigation')
            @endif

            {{-- Header opcional --}}
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            {{-- Contenido principal --}}
            <main>
                @isset($slot)
                    {{ $slot }}
                @else
                    @yield('content')
                @endisset
            </main>
        </div>

        {{-- Scripts de reCAPTCHA movidos a resources/js/recaptcha.js --}}
        @if(config('services.recaptcha.site'))
            {{-- El script se carga vía Vite --}}
        @endif

        {{-- Scripts de debug movidos a resources/js/recaptcha.js --}}
        @if(app()->environment('local'))
            {{-- Debug ahora en el JS --}}
        @endif
        
        {{-- Inclusión del banner de cookies --}}
        @include('components.cookie-consent')
    </body>
</html>

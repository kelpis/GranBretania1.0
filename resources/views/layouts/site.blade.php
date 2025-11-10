<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Gran Bretania')</title>

    {{-- Fuentes --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inconsolata:wght@400;500&family=Raleway:wght@400;600;700&display=swap"
        rel="stylesheet">

    {{-- Favicons --}}
    @include('partials.favicons')

    {{-- Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-dvh flex flex-col bg-beige2">
    {{-- Top bar idiomas --}}
    <div class="w-full text-sm text-right px-4 py-2">
        <nav class="inline-flex gap-3 uppercase tracking-wider">
            <a href="{{ url('?lang=es') }}" class="hover:underline">ES</a>
            <span>/</span>
            <a href="{{ url('?lang=en') }}" class="hover:underline">EN</a>
            <a class="btn-secondary text-white !px-4 !py-2 lowercase">Acceder</a>
        </nav>
    </div>

    {{-- Header inspirado en tu referencia --}}
    <!-- Reducido el padding vertical para acercar logo y menú -->
    <header class="py-4">
        <div class="container mx-auto px-4 text-center">
            <a href="{{ route('home') }}" class="inline-block">
                {{-- Sustituye por tu SVG/PNG --}}
                <!-- Logo aumentado: h-64 en móvil, h-80 en pantallas md (~256px / 320px) -->
                <img src="{{ asset('images/logoSinMargen.png') }}" alt="Gran Bretania" class="mx-auto h-64 md:h-80 w-auto">
            </a>

            <!-- Reducida la separación superior para pegar el menú al logo -->
            <nav class="mt-2 flex flex-wrap items-center justify-center gap-16 text-azul tracking-wider">
                <a class="hover:underline text-lg md:text-xl font-medium px-2">Inicio</a>
                <a class="hover:underline text-lg md:text-xl font-medium px-2">Clases</a>
                <a class="hover:underline text-lg md:text-xl font-medium px-2">Traducciones</a>
                <a class="hover:underline text-lg md:text-xl font-medium px-2">Sobre mí</a>
                <a class="hover:underline text-lg md:text-xl font-medium px-2">FAQ</a>
                <a href="{{ route('contact.create') }}" class="hover:underline text-lg md:text-xl font-medium px-2">Contacto</a>
            </nav>
        </div>
    </header>

    {{-- Contenido --}}
    <main class="flex-1">
        @yield('content')
    </main>

    {{-- Footer azul corporativo --}}
    <footer class="bg-azul text-white mt-12">
        <div class="container mx-auto px-4 py-10 grid md:grid-cols-3 gap-8">
            <div>
                <h3 class="font-semibold mb-3">Gran Bretania</h3>
                <p class="text-sm opacity-80">Enseñanza de inglés y traducciones.</p>
            </div>
            <div>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('privacy') }}" class="hover:underline">Política de privacidad</a>
                    </li>
                    <li><a href="{{ route('cookies.policy') }}" class="hover:underline">Política de cookies</a></li>
                    <li><a class="hover:underline">Aviso legal</a></li>
                </ul>
            </div>
            <div class="text-sm">
                <p class="opacity-80">info@granbretania.test</p>
                <p class="opacity-80">+34 000 000 000</p>
            </div>
        </div>
    </footer>
</body>

</html>
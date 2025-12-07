{{--
    Layout principal: site.blade.php
    Propósito: layout global con header, footer, carga de Vite y manejo de tema oscuro.
    Notas: usado por la mayoría de vistas; evitar duplicar navs en plantillas que extienden este layout.
--}}

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Gran Bretania')</title>
    {{-- Script del tema movido a `resources/js/theme.js` y cargado desde Vite (import en `app.js`) --}}
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

<body class="min-h-dvh flex flex-col bg-beige2 px-4 md:px-8 lg:px-12">

    @section('header')
    <header
        class="sticky top-0 z-40 bg-beige/95 dark:bg-slate-800/90 backdrop-blur supports-[backdrop-filter]:bg-beige/80 shadow-sm -mx-4 md:-mx-8 lg:-mx-12">

        <div class="container mx-auto px-4">
            <div x-data="{ open: false }" @keydown.escape="open = false" @click.away="open = false"
                class="h-20 flex items-center justify-between gap-4 relative">

                {{-- LEFT: logo + enlaces --}}
                <div class="flex items-center gap-6">
                    {{-- LOGO --}}
                    <a href="{{ route('home') }}" class="flex items-center gap-3 shrink-0">
                        <img src="{{ asset('images/logoMonocroma.png') }}" alt="Gran Bretania"
                            class="h-20 w-auto dark:invert dark:brightness-0">
                    </a>

                    {{-- ENLACES (escritorio) --}}
                    <nav class="flex items-center gap-4">
                        {{-- Desktop links: visible en lg+ --}}
                        <div class="hidden lg:flex items-center gap-8 text-azul dark:text-beige2 tracking-wide text-lg">
                            <a href="{{ route('home') }}" class="hover:underline">Inicio</a>
                            <a href="{{ route('clases') }}" class="hover:underline">Clases</a>
                            <a href="{{ route('traducciones') }}" class="hover:underline">Traducciones</a>
                            <a href="{{ route('sobremi') }}" class="hover:underline">Sobre mí</a>
                            <a href="{{ route('faq') }}" class="hover:underline">FAQ</a>
                            <a href="{{ route('contact.create') }}" class="hover:underline">Contacto</a>
                        </div>


                        
                    </nav>
                </div>

                {{-- RIGHT: botón Acceder y hamburguesa móvil --}}
                <div class="flex items-center gap-3">
                     <div class="lg:hidden">
                        <button @click="open = ! open" :aria-expanded="open.toString()" aria-controls="mobile-menu"
                            class="inline-flex items-center justify-center p-2 rounded-md text-azul border border-azul/20 bg-white/90">
                            <svg x-show="!open" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                            <svg x-show="open" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                </div>

                <div class="hidden lg:flex items-center gap-3">
                    {{-- Botón modo oscuro --}}
                    <button type="button" onclick="
        const html = document.documentElement;
        const isDark = html.classList.toggle('dark');
        localStorage.setItem('theme', isDark ? 'dark' : 'light');
    " class="relative inline-flex items-center w-12 h-6 rounded-full transition-colors
           bg-azul/20 dark:bg-slate-700 border border-azul/40 dark:border-gray-500">

                        <!-- CÍRCULO -->
                        <span class="absolute left-0 top-0 h-6 w-6 bg-white dark:bg-yellow-300 rounded-full shadow
                 transform transition-transform duration-300
                 dark:translate-x-6"></span>

                        <!-- ICONOS -->
                        <span class="absolute left-1 top-1 text-[10px] dark:hidden">🌙</span>
                        <span class="absolute right-1 top-1 hidden dark:inline text-[10px]">☀️</span>
                    </button>

                    @auth
                        <a href="{{ (auth()->check() && auth()->user()->is_admin) ? route('admin.index') : route('dashboard') }}" class="btn-three text-beige2 !py-2 !px-4 !mr-8">{{ auth()->user()->name }}</a>
                    @else
                        <a href="{{ route('login') }}" class="btn-three text-beige2 !py-2 !px-4 !mr-8">Acceder</a>
                    @endauth
                </div>
            </div>

            {{-- Mobile menu panel (aparece cuando open === true) --}}
            <div x-show="open" x-cloak id="mobile-menu"
                class="lg:hidden absolute inset-x-0 top-full z-50 bg-beige/95 backdrop-blur-sm shadow-sm dark:bg-slate-800/95">
                <div class="px-4 py-4 text-lg max-h-[calc(100vh-5rem)] overflow-auto">
                    <div class="flex items-center justify-start gap-3 mb-3">
                        <button type="button"
                            onclick="(function(){const html=document.documentElement;const isDark=html.classList.toggle('dark');localStorage.setItem('theme', isDark? 'dark':'light');})()"
                            aria-label="Alternar modo oscuro"
                            class="inline-flex items-center justify-center px-2 py-1 rounded-full border border-azul/40 dark:border-gray-500 text-lg text-azul dark:text-gray-100 bg-white/80 dark:bg-slate-800/80 shadow-sm">
                            <span class="dark:hidden">🌙</span>
                            <span class="hidden dark:inline">☀️</span>
                        </button>
                    </div>
                    <a href="{{ route('home') }}" @click="open = false" class="block py-2 hover:underline">Inicio</a>
                    <a href="{{ route('clases') }}" @click="open = false" class="block py-2 hover:underline">Clases</a>
                    <a href="{{ route('traducciones') }}" @click="open = false"
                        class="block py-2 hover:underline">Traducciones</a>
                    <a href="{{ route('sobremi') }}" @click="open = false" class="block py-2 hover:underline">Sobre
                        mí</a>
                    <a href="{{ route('faq') }}" @click="open = false" class="block py-2 hover:underline">FAQ</a>
                    <a href="{{ route('contact.create') }}" @click="open = false"
                        class="block py-2 hover:underline">Contacto</a>



                    {{-- Acceder (visible en móvil dentro del menú) --}}
                    @auth
                        <a href="{{ (auth()->check() && auth()->user()->is_admin) ? route('admin.index') : route('dashboard') }}" @click="open = false"
                            class="block w-full mt-3 btn-three text-beige2 text-center !py-2 !px-4">{{ auth()->user()->name }}</a>

                        <form method="POST" action="{{ route('logout') }}" class="mt-2">
                            @csrf
                            <button type="submit" class="w-full text-left text-azul dark:text-beige2 hover:underline py-2">Cerrar sesión</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" @click="open = false"
                            class="block w-full mt-3 btn-three text-beige2 text-center !py-2 !px-4">Acceder</a>
                    @endauth
                </div>
            </div>

        </div>
        </div>
    </header>
    @show


    {{-- Contenido --}}
    <main class="flex-1">
        @yield('content')
    </main>

    {{-- Footer azul corporativo --}}
    <footer class="bg-azul dark:bg-slate-900/90 text-white mt-12 -mx-4 md:-mx-8 lg:-mx-12">

        <div class="px-4 sm:px-6 lg:px-8">
            <div class="container mx-auto px-4 py-10 flex flex-col md:flex-row gap-8">
                <div class="md:basis-1/2">
                    <h3 class="font-semibold mb-3">Gran Bretania</h3>
                    <p class="text-sm opacity-80">Enseñanza de inglés y traducciones.</p>
                </div>
                <div class="md:basis-1/4">
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('privacy') }}" class="hover:underline">Política de privacidad</a>
                        </li>
                        <li><a href="{{ route('cookies.policy') }}" class="hover:underline">Política de cookies</a>
                        </li>
                        <li><a href="{{ route('condiciones') }}" class="hover:underline">Términos del servicio</a>
                        </li>
                        <li><a href="{{ route('aviso') }}" class="hover:underline">Aviso legal</a></li>
                    </ul>
                </div>
                <div class="text-sm md:basis-1/4">
                    <p class="opacity-80">info@granbretania.com</p>
                    <p class="opacity-80">+34 000 000 000</p>

                    {{-- Iconos redes sociales --}}
                    <div class="mt-4 flex items-center gap-3" aria-label="Redes sociales">
                        <a href="https://instagram.com" target="_blank" rel="noopener noreferrer" aria-label="Instagram"
                            class="text-white hover:opacity-90 hover:scale-105 transition-transform transition-opacity duration-150">
                            <img src="{{ asset('images/instagram.svg') }}" alt="Instagram"
                                class="w-5 h-5 object-contain">
                        </a>

                        <a href="https://facebook.com" target="_blank" rel="noopener noreferrer" aria-label="Facebook"
                            class="text-white hover:opacity-90 hover:scale-105 transition-transform transition-opacity duration-150">
                            <img src="{{ asset('images/facebook.svg') }}" alt="Facebook" class="w-5 h-5 object-contain">
                        </a>

                        <a href="https://x.com" target="_blank" rel="noopener noreferrer" aria-label="X"
                            class="text-white hover:opacity-90 hover:scale-105 transition-transform transition-opacity duration-150">
                            <img src="{{ asset('images/x.svg') }}" alt="X" class="w-5 h-5 object-contain">
                        </a>

                        <a href="https://linkedin.com" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn"
                            class="text-white hover:opacity-80">
                            <!-- LinkedIn icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-5 h-5 fill-current"
                                aria-hidden="true">
                                <path
                                    d="M4.98 3.5a2.5 2.5 0 1 1 0 5 2.5 2.5 0 0 1 0-5zM3 9h4v12H3zM9 9h3.6v1.7h.05c.5-.95 1.7-1.95 3.5-1.95 3.75 0 4.45 2.47 4.45 5.67V21H16v-5.3c0-1.27-.02-2.9-1.77-2.9-1.77 0-2.04 1.38-2.04 2.8V21H9z" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
        {{-- Banner de cookies: componente global incluido en el layout público --}}
        @include('components.cookie-consent')
</body>

</html>
@if(config('services.recaptcha.site'))
    <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const siteKey = "{{ config('services.recaptcha.site') }}";

            document.querySelectorAll('form[data-grecaptcha="v3"]').forEach(function (form) {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    grecaptcha.ready(function () {
                        const action = form.getAttribute('data-recaptcha-action') || 'submit';
                        grecaptcha.execute(siteKey, { action: action }).then(function (token) {
                            let input = form.querySelector('input[name="g-recaptcha-response"]');
                            if (!input) {
                                input = document.createElement('input');
                                input.type = 'hidden';
                                input.name = 'g-recaptcha-response';
                                form.appendChild(input);
                            }
                            input.value = token;
                            form.submit();
                        }).catch(function (err) {
                            console.error('reCAPTCHA execute failed', err);
                            let errEl = form.querySelector('.recaptcha-error');
                            if (!errEl) {
                                errEl = document.createElement('p');
                                errEl.className = 'recaptcha-error text-red-600 text-sm mt-2';
                                const submit = form.querySelector('[type="submit"]');
                                if (submit && submit.parentNode) {
                                    submit.parentNode.insertBefore(errEl, submit.nextSibling);
                                } else {
                                    form.appendChild(errEl);
                                }
                            }
                            errEl.textContent = 'No se pudo verificar reCAPTCHA en tu navegador. Prueba en una ventana privada o desactiva extensiones que bloqueen scripts.';
                        });
                    });
                });
            });
        });
    </script>
@endif

@if(app()->environment('local'))
    <script>
        console.log('DEBUG: site layout reCAPTCHA key present?', {{ config('services.recaptcha.site') ? 'true' : 'false' }});
        document.addEventListener('DOMContentLoaded', function () {
            console.log('DEBUG: site layout grecaptcha defined?', (typeof grecaptcha !== 'undefined'));
        });
    </script>
   
@endif
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

    @include('partials.favicons')

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @if(auth()->check() && auth()->user()->is_admin)
                @include('layouts.navigationAdmin')
            @else
                @include('layouts.navigation')
            @endif

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                @isset($slot)
                    {{ $slot }}
                @else
                    @yield('content')
                @endisset
            </main>
        </div>
        @if(config('services.recaptcha.site'))
            <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site') }}"></script>

            <script>
            document.addEventListener('DOMContentLoaded', function () {
                const siteKey = "{{ config('services.recaptcha.site') }}";

                document.querySelectorAll('form[data-grecaptcha="v3"]').forEach(function(form) {
                    form.addEventListener('submit', function (e) {
                        e.preventDefault();
                        grecaptcha.ready(function() {
                            const action = form.getAttribute('data-recaptcha-action') || 'submit';
                            grecaptcha.execute(siteKey, {action: action}).then(function(token) {
                                let input = form.querySelector('input[name="g-recaptcha-response"]');
                                if (!input) {
                                    input = document.createElement('input');
                                    input.type = 'hidden';
                                    input.name = 'g-recaptcha-response';
                                    form.appendChild(input);
                                }
                                input.value = token;
                                form.submit();
                            }).catch(function(err) {
                                // Evitar "uncaught (in promise) null" y mostrar info útil
                                console.error('reCAPTCHA execute failed', err);
                                let errEl = form.querySelector('.recaptcha-error');
                                if (!errEl) {
                                    errEl = document.createElement('p');
                                    errEl.className = 'recaptcha-error text-red-600 text-sm mt-2';
                                    // intentar insertar antes del botón submit si existe
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
                // Debug helper: muestra en consola si la clave existe y si grecaptcha está disponible
                console.log('DEBUG: reCAPTCHA site key present?', {{ config('services.recaptcha.site') ? 'true' : 'false' }});
                document.addEventListener('DOMContentLoaded', function () {
                    console.log('DEBUG: grecaptcha defined?', (typeof grecaptcha !== 'undefined'));
                    if (typeof grecaptcha !== 'undefined') {
                        try {
                            grecaptcha.ready(function() {
                                console.log('DEBUG: grecaptcha.ready executed');
                            });
                        } catch (e) {
                            console.error('DEBUG: grecaptcha.ready error', e);
                        }
                    }
                });
            </script>

            <style>
                .recaptcha-debug-badge {
                    position: fixed;
                    left: 8px;
                    bottom: 8px;
                    background: rgba(0,0,0,0.7);
                    color: #fff;
                    padding: 6px 8px;
                    font-size: 12px;
                    border-radius: 6px;
                    z-index: 999999;
                }
            </style>
            
        @endif
        @include('components.cookie-consent')
    </body>
</html>

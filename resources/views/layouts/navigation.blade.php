{{--
Partial: navigation.blade.php
barra de navegación principal (desktop + mobile) para usuarios no-admin.
--}}

@if(auth()->check() && auth()->user()->is_admin)
    @include('layouts.navigationAdmin')
@else
    <nav x-data="{ open: false }"
        class="w-screen bg-beige dark:bg-slate-800/90 dark:text-beige2 border-b border-gray-100 dark:border-slate-700 -mx-4 md:-mx-8 lg:-mx-12">

        <div class="w-full">
            <div class="w-full flex items-center justify-between h-16">
                {{-- Logo --}}
                <div class="flex items-center gap-6">
                    <div class="shrink-0 flex items-center pl-3 sm:pl-6">
                        <a href="{{ route('home') }}" class="flex items-center gap-3 shrink-0">
                            <img src="{{ asset('images/logoMonocroma.png') }}" alt="Gran Bretania"
                                class="h-20 w-auto dark:invert dark:brightness-0">
                        </a>
                    </div>

                    {{-- Navigation Enlaces --}}
                    <div class="hidden space-x-8 lg:flex text-azul dark:text-white tracking-wide">
                        {{-- Enlace a reservar clase --}}
                        <a href="{{ route('bookings.create') }}" class="px-2 py-1 text-sm font-medium
                                        {{ request()->routeIs('bookings.create')
                                        ? 'text-azul border-b-2 border-azul dark:text-white dark:border-white'
                                        : 'text-azul/80 hover:text-azul dark:text-white/80 dark:hover:text-white' }}">
                            {{ __('Reservar clase') }}
                        </a>

                        {{-- Enlace a mis clases --}}
                        <a href="{{ auth()->check() && auth()->user()->is_admin ? route('admin.bookings.index') : route('user.bookings.index') }}"
                            class="px-2 py-1 text-sm font-medium 
                                            {{ request()->routeIs('user.bookings.*') || request()->routeIs('admin.bookings.*')
                                            ? 'text-azul border-b-2 border-azul dark:text-white dark:border-white'
                                            : 'text-azul/80 hover:text-azul dark:text-white/80 dark:hover:text-white' }}">
                            {{ __('Mis clases') }}
                        </a>

                        {{-- Enlace a solicitar traducción --}}
                        <a href="{{ route('translation.create') }}" class="px-2 py-1 text-sm font-medium 
                                            {{ request()->routeIs('translation.create')
                                            ? 'text-azul border-b-2 border-azul dark:text-white dark:border-white'
                                            : 'text-azul/80 hover:text-azul dark:text-white/80 dark:hover:text-white' }}">
                            {{ __('Solicitar traducción') }}
                        </a>

                        {{-- Enlace a mis traducciones --}}
                        @auth
                                    <a href="{{ auth()->user()->is_admin ? route('admin.translations.index') : route('user.translations.index') }}"
                                        class="px-2 py-1 text-sm font-medium 
                                            {{ request()->routeIs('user.translations.*') || request()->routeIs('admin.translations.*')
                                            ? 'text-azul border-b-2 border-azul dark:text-white dark:border-white'
                                            : 'text-azul/80 hover:text-azul dark:text-white/80 dark:hover:text-white' }}">
                                        {{ __('Mis traducciones') }}
                                    </a>
                        @endauth

                        {{-- Enlace a contacto --}}

                        <a href="{{ auth()->check() && auth()->user()->is_admin ? route('admin.index') : route('contact.create') }}"
                            class="px-2 py-1 text-sm font-medium 
                                            {{ request()->routeIs('contact.create') || request()->routeIs('admin.index')
                                            ? 'text-azul border-b-2 border-azul dark:text-white dark:border-white'
                                            : 'text-azul/80 hover:text-azul dark:text-white/80 dark:hover:text-white' }}">
                            {{ __('Contacto') }}
                        </a>


                    </div>
                </div>

                {{-- Barra derecha: modo oscuro, usuario --}}
                <div class="flex items-center gap-3 pr-3 sm:pr-6">
                    <div class="hidden lg:flex lg:items-center lg:ml-6">

                        {{-- Botón modo oscuro --}}
                        <div class="me-3">
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

                        </div>

                        {{-- Dropdown de usuario --}}
                        @auth

                            <!-- User dropdown -->
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">

                                    <button
                                        class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium
                                        bg-beige2 dark:bg-slate-800/80 dark:text-beige2 text-azul border border-azul/20 dark:border-slate-600 shadow-sm
                                        hover:bg-azul hover:text-beige2 transition duration-150 ease-in-out">

                                        <div class="font-semibold">{{ auth()->user()->name }}</div>

                                        <div class="ms-2">
                                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>

                                </x-slot>

                                <x-slot name="content">

                                    {{-- Perfil --}}
                                    <x-dropdown-link :href="route('profile.edit')"
                                        class="hover:bg-azul/10 hover:text-azul transition">
                                        {{ __('Perfil') }}
                                    </x-dropdown-link>

                                    <!-- Logout -->
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf

                                        <x-dropdown-link :href="route('logout')"
                                            class="hover:bg-rojo/10 hover:text-rojo transition"
                                            onclick="event.preventDefault(); this.closest('form').submit();">
                                            {{ __('Cerrar sesión') }}
                                        </x-dropdown-link>
                                    </form>

                                </x-slot>
                            </x-dropdown>

                        @endauth
                    </div>

                    {{-- Enlaces para invitados --}}
                    @guest
                        <div class="hidden lg:flex items-center gap-3">
                            <a href="{{ route('login') }}"
                                class="text-sm text-gray-600 dark:text-beige2 hover:text-gray-900 dark:hover:text-beige2">Iniciar
                                sesión</a>
                            <a href="{{ route('register') }}"
                                class="text-sm text-gray-600 dark:text-beige2 hover:text-gray-900 dark:hover:text-beige2">Registrarse</a>
                        </div>
                    @endguest

                    {{-- Hamburger para móvil --}}
                    <div class="pr-3 lg:pr-6 flex lg:hidden">
                        <button @click="open = ! open"
                            class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-300 hover:text-gray-500 dark:hover:text-beige2 hover:bg-gray-100 dark:hover:bg-slate-800 focus:outline-none focus:bg-gray-100 dark:focus:bg-slate-800 focus:text-gray-500 transition duration-150 ease-in-out">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                                    stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden"
                                    stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>



        {{-- Responsive Menu --}}
        <div :class="{'block': open, 'hidden': ! open}"
            class="hidden lg:hidden w-screen bg-beige dark:bg-slate-800/95 dark:text-beige2">

            {{-- Botón modo oscuro en móvil --}}
            <div class="px-4 py-2 border-b border-gray-100 dark:border-slate-700 flex justify-end">
                <button type="button"
                    onclick="(function(){const html=document.documentElement;const isDark=html.classList.toggle('dark');localStorage.setItem('theme', isDark? 'dark':'light');})()"
                    aria-label="Alternar modo oscuro"
                    class="inline-flex items-center justify-center px-2 py-1 rounded-full border border-azul/40 dark:border-gray-500 text-lg text-azul dark:text-gray-100 bg-white/80 dark:bg-slate-800/80 shadow-sm">
                    <span class="dark:hidden">🌙</span>
                    <span class="hidden dark:inline">☀️</span>
                </button>
            </div>
            {{-- Enlaces en menú móvil --}}
            <div class="pt-2 pb-3 space-y-1">


                <!-- Enlaces añadidos: Reservar clase y Solicitar traducción -->
                <x-responsive-nav-link :href="route('bookings.create')" :active="request()->routeIs('bookings.create')">
                    {{ __('Reservar clase') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('translation.create')"
                    :active="request()->routeIs('translation.create')">
                    {{ __('Solicitar traducción') }}
                </x-responsive-nav-link>

                {{-- Enlaces para usuarios autenticados --}}
                @auth
                    <x-responsive-nav-link :href="(auth()->user()->is_admin) ? route('admin.bookings.index') : route('user.bookings.index')" :active="request()->routeIs('user.bookings.*') || request()->routeIs('admin.bookings.*')">
                        {{ __('Mis reservas') }}
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="(auth()->user()->is_admin) ? route('admin.translations.index') : route('user.translations.index')" :active="request()->routeIs('user.translations.*') || request()->routeIs('admin.translations.*')">
                        {{ __('Mis traducciones') }}
                    </x-responsive-nav-link>
                @endauth

                <x-responsive-nav-link :href="(auth()->check() && auth()->user()->is_admin) ? route('admin.index') : route('contact.create')" :active="request()->routeIs('contact.create') || request()->routeIs('admin.index')">
                    {{ __('Contacto') }}
                </x-responsive-nav-link>


            </div>


            {{--Opciones de ajuste --}}
            <div class="pt-4 pb-1 border-t border-gray-200 dark:border-slate-700">
                <div class="px-4">
                    {{-- Información del usuario --}}
                    @auth
                        <div class="font-medium text-base text-gray-800 dark:text-beige2">{{ auth()->user()->name }}</div>
                        <div class="font-medium text-sm text-gray-500 dark:text-beige2">{{ auth()->user()->email }}</div>
                    @else
                        <div class="font-medium text-base text-gray-800 dark:text-beige2">Invitado</div>
                        <div class="font-medium text-sm text-gray-500 dark:text-beige2">No has iniciado sesión</div>
                    @endauth
                </div>

                {{-- Enlaces de perfil --}}
                <div class="mt-3 space-y-1">
                    @auth
                        <x-responsive-nav-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-responsive-nav-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-responsive-nav-link>
                        </form>
                    @else
                        <x-responsive-nav-link :href="route('login')">
                            {{ __('Login') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('register')">
                            {{ __('Register') }}
                        </x-responsive-nav-link>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
@endif
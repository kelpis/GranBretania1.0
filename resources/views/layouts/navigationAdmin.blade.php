<nav x-data="{ open: false }"
    class="w-screen bg-beige border-b border-gray-100 dark:bg-slate-800 dark:border-slate-700 -mx-4 sm:-mx-6 lg:-mx-8">

    <div class="w-full">
        <div class="w-full flex items-center justify-between h-16">

            <div class="flex items-center gap-6">
                <div class="shrink-0 flex items-center pl-3 sm:pl-6">
                    <a href="{{ route('admin.index') }}" class="flex items-center gap-3 shrink-0">
                        <img src="{{ asset('images/logoMonocroma.png') }}"
                             alt="Gran Bretania"
                             class="h-16 w-auto dark:invert dark:brightness-0">
                    </a>
                </div>

                {{-- ENLACES DESKTOP --}}
                <div class="hidden sm:flex space-x-8 text-azul tracking-wide dark:text-beige2">

                    <x-nav-link :href="route('admin.bookings.index')"
                        :active="request()->routeIs('admin.bookings.*')"
                        class="dark:text-beige2 dark:hover:text-white dark:[&.active]:border-beige2">

                        {{ __('Mis clases') }}
                    </x-nav-link>

                    <x-nav-link :href="route('admin.translations.index')"
                        :active="request()->routeIs('admin.translations.*')"
                        class="dark:text-beige2 dark:hover:text-white dark:[&.active]:border-beige2">

                        {{ __('Mis traducciones') }}
                    </x-nav-link>

                    <x-nav-link :href="route('admin.availability.index')"
                        :active="request()->routeIs('admin.availability.*')"
                        class="dark:text-beige2 dark:hover:text-white dark:[&.active]:border-beige2">

                        {{ __('Disponibilidad') }}
                    </x-nav-link>

                </div>
            </div>

            {{-- DROPDOWN USUARIO --}}
            <div class="hidden sm:flex sm:items-center sm:ml-6 pr-3 sm:pr-6">

                {{-- Botón modo oscuro (admin) --}}
                <div class="me-3">
                    <button type="button" onclick="
                                const html = document.documentElement;
                                const isDark = html.classList.toggle('dark');
                                localStorage.setItem('theme', isDark ? 'dark' : 'light');
                            "
                        class="inline-flex items-center justify-center px-2 py-1 rounded-full border border-azul/40 dark:border-gray-500 text-xs text-azul dark:text-gray-100 bg-white/80 dark:bg-slate-800/80 shadow-sm">
                        <span class="dark:hidden">🌙</span>
                        <span class="hidden dark:inline">☀️</span>
                    </button>
                </div>

                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium
                                    bg-beige2 text-azul border border-azul/20 shadow-sm
                                    hover:bg-azul hover:text-beige2
                                    dark:bg-slate-700 dark:text-beige2 dark:border-slate-600
                                    dark:hover:bg-slate-600">
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

                            <x-dropdown-link :href="route('profile.edit')"
                                class="hover:bg-azul/10 hover:text-azul dark:hover:bg-slate-800 dark:hover:text-beige2">
                                {{ __('Perfil') }}
                            </x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();"
                                    class="hover:bg-rojo/10 hover:text-rojo dark:hover:bg-rose-900 dark:hover:text-rose-100">
                                    {{ __('Cerrar sesión') }}
                                </x-dropdown-link>
                            </form>

                        </x-slot>
                    </x-dropdown>
                @endauth
            </div>

            {{-- BOTÓN HAMBURGUESA --}}
            <div class="pr-3 sm:pr-6 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md
                           text-gray-400 hover:text-gray-500 hover:bg-gray-100
                           focus:outline-none focus:bg-gray-100 focus:text-gray-500
                           dark:text-beige2 dark:hover:text-white dark:hover:bg-slate-700 dark:focus:bg-slate-700">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }"
                              class="inline-flex"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }"
                              class="hidden"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

        </div>
    </div>

    {{-- MENU MÓVIL --}}
    <div :class="{'block': open, 'hidden': ! open}"
         class="hidden sm:hidden w-screen bg-beige dark:bg-slate-800">

        <div class="pt-2 pb-3 space-y-1">

            <x-responsive-nav-link :href="route('admin.bookings.index')"
                :active="request()->routeIs('admin.bookings.*')"
                class="dark:text-beige2 dark:hover:text-white dark:[&.active]:border-beige2">
                {{ __('Mis clases') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('admin.translations.index')"
                :active="request()->routeIs('admin.translations.*')"
                class="dark:text-beige2 dark:hover:text-white dark:[&.active]:border-beige2">
                {{ __('Mis traducciones') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('admin.availability.index')"
                :active="request()->routeIs('admin.availability.*')"
                class="dark:text-beige2 dark:hover:text-white dark:[&.active]:border-beige2">
                {{ __('Disponibilidad') }}
            </x-responsive-nav-link>

        </div>


        {{-- PERFIL / LOGOUT --}}
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-slate-700">

            <div class="px-4">
                @auth
                    <div class="font-medium text-base text-gray-800 dark:text-beige2">{{ auth()->user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500 dark:text-beige2/80">{{ auth()->user()->email }}</div>
                @else
                    <div class="font-medium text-base text-gray-800 dark:text-beige2">Invitado</div>
                    <div class="font-medium text-sm text-gray-500 dark:text-beige2/80">No has iniciado sesión</div>
                @endauth
            </div>

            <div class="mt-3 space-y-1 px-4">
                @auth
                    <x-responsive-nav-link :href="route('profile.edit')"
                        class="dark:text-beige2 dark:hover:text-white">
                        {{ __('Perfil') }}
                    </x-responsive-nav-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();"
                            class="dark:text-beige2 dark:hover:text-white">
                            {{ __('Cerrar sesión') }}
                        </x-responsive-nav-link>
                    </form>

                @else
                    <x-responsive-nav-link :href="route('login')" class="dark:text-beige2 dark:hover:text-white">
                        {{ __('Iniciar sesión') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('register')" class="dark:text-beige2 dark:hover:text-white">
                        {{ __('Registrarse') }}
                    </x-responsive-nav-link>
                @endauth
            </div>

        </div>

    </div>
</nav>

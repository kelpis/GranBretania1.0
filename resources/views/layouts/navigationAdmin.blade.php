<nav x-data="{ open: false }" class="w-screen bg-beige border-b border-gray-100 -mx-4 sm:-mx-6 lg:-mx-8">
        <div class="w-full">
        <div class="w-full flex items-center justify-between h-16">
            <div class="flex items-center gap-6">
                <div class="shrink-0 flex items-center pl-3 sm:pl-6">
                    <a href="{{ route('admin.index') }}" class="flex items-center gap-3 shrink-0">
                        <img src="{{ asset('images/logoMonocroma.png') }}" alt="Gran Bretania" class="h-16 w-auto">
                    </a>
                </div>

                <div class="hidden sm:flex space-x-8 text-azul tracking-wide">
                    <x-nav-link :href="route('admin.bookings.index')" :active="request()->routeIs('admin.bookings.*')">
                        {{ __('Mis clases') }}
                    </x-nav-link>

                    <x-nav-link :href="route('admin.translations.index')"
                        :active="request()->routeIs('admin.translations.*')">
                        {{ __('Mis traducciones') }}
                    </x-nav-link>

                    <x-nav-link :href="route('admin.availability.index')"
                        :active="request()->routeIs('admin.availability.*')">
                        {{ __('Disponibilidad') }}
                    </x-nav-link>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ml-6 pr-3 sm:pr-6">

                @auth

                    <!-- User dropdown -->
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">

                            <button class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium
                                   bg-beige2 text-azul border border-azul/20 shadow-sm
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

                            {{-- Profile --}}
                            <x-dropdown-link :href="route('profile.edit')"
                                class="hover:bg-azul/10 hover:text-azul transition">
                                {{ __('Perfil') }}
                            </x-dropdown-link>

                            <!-- Logout -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')" class="hover:bg-rojo/10 hover:text-rojo transition"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Cerrar sesión') }}
                                </x-dropdown-link>
                            </form>

                        </x-slot>
                    </x-dropdown>

                @endauth
            </div>

            <div class="pr-3 sm:pr-6 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden w-screen bg-beige">
        <div class="pt-2 pb-3 space-y-1">
            
            <x-responsive-nav-link :href="route('admin.bookings.index')"
                :active="request()->routeIs('admin.bookings.*')">
                {{ __('Mis clases') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('admin.translations.index')"
                :active="request()->routeIs('admin.translations.*')">
                {{ __('Mis traducciones') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('admin.availability.index')"
                :active="request()->routeIs('admin.availability.*')">
                {{ __('Disponibilidad') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options (Perfil / Cerrar sesión) -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                @auth
                    <div class="font-medium text-base text-gray-800">{{ auth()->user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ auth()->user()->email }}</div>
                @else
                    <div class="font-medium text-base text-gray-800">Invitado</div>
                    <div class="font-medium text-sm text-gray-500">No has iniciado sesión</div>
                @endauth
            </div>

            <div class="mt-3 space-y-1 px-4">
                @auth
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Perfil') }}
                    </x-responsive-nav-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                            {{ __('Cerrar sesión') }}
                        </x-responsive-nav-link>
                    </form>
                @else
                    <x-responsive-nav-link :href="route('login')">
                        {{ __('Iniciar sesión') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('register')">
                        {{ __('Registrarse') }}
                    </x-responsive-nav-link>
                @endauth
            </div>
        </div>
    </div>
    </nav>
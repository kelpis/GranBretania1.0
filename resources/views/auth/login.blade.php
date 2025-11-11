@extends('layouts.site')



@section('content')

    {{-- Separar del header y centrar título y párrafo debajo del h1 --}}
    {{-- Usamos my-12 para tener la misma separación arriba y abajo del bloque --}}
    <div class="max-w-2xl mx-auto my-12 text-center">
        <h2 class="text-azul text-3xl font-semibold mb-2">¡Bienvenid@ de nuevo!</h2>
        <p class="text-gray-700 text-base leading-relaxed mx-auto max-w-xl">
            Nos alegra verte por aquí. Accede para continuar aprendiendo inglés o enviar tus nuevos encargos de traducción.
        </p>
    </div>
    <div class="container mx-auto px-4">
        <div class="max-w-md mx-auto bg-azul text-beige2 rounded-lg shadow-lg p-8">

            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div>
                    <x-input-label for="email" class="text-beige2" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full bg-white text-azul rounded p-2" type="email"
                        name="email" :value="old('email')" required autofocus />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mt-4">


                    <x-input-label for="password" class="text-beige2" :value="__('Password')" />

                    <x-text-input id="password" class="block mt-1 w-full bg-white text-azul rounded p-2" type="password"
                        name="password" required autocomplete="current-password" />

                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember Me -->
                <div class="block mt-4">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox"
                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                        <span class="ml-2 text-sm text-beige2">{{ __('Remember me') }}</span>
                    </label>
                </div>

                <div class="flex items-center justify-end mt-6">
                    @if (Route::has('password.request'))
                        <a class="underline text-sm text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif

                    <button type="submit" class="btn-secondary ml-3 hover:!bg-beige hover:!text-negro">{{ __('Log in') }}</button>
                </div>
            </form>
             <p class="mt-8 text-center text-sm text-beige2">
                ¿Nuevo en Gran Britania?
                <a href="{{ route('register') }}" class="underline font-semibold hover:text-rojo transition">
                Crea tu cuenta 
                </a>
                en un minuto.
            </p>
        </div>
    </div>
@endsection
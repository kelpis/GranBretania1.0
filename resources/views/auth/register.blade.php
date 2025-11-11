@extends('layouts.site')


@section('content')
    <div class="max-w-2xl mx-auto my-8 text-center">
        <h2 class="text-azul text-3xl font-semibold mb-2">¡Hello!</h2>
        <p class="text-gray-700 text-base leading-relaxed mx-auto max-w-xl">
            Regístrate para acceder a tu área personal y gestionar tus clases, presupuestos de traducción y todos tus servicios en un solo lugar.
        </p>
    </div>
    {{-- Copiado estilo del login: contenedor + card azul con texto beige --}}
    <div class="container mx-auto px-4">
        <div class="max-w-md mx-auto mt-6 bg-azul text-beige2 rounded-lg shadow-lg p-8">


            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div>
                    <x-input-label for="name" class="text-beige2" :value="__('Name')" />
                    <x-text-input id="name" class="block mt-1 w-full bg-white text-azul rounded p-2" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Email Address -->
                <div class="mt-4">
                    <x-input-label for="email" class="text-beige2" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full bg-white text-azul rounded p-2" type="email" name="email" :value="old('email')" required autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="password" class="text-beige2" :value="__('Password')" />

                    <x-text-input id="password" class="block mt-1 w-full bg-white text-azul rounded p-2"
                                    type="password"
                                    name="password"
                                    required autocomplete="new-password" />

                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div class="mt-4">
                    <x-input-label for="password_confirmation" class="text-beige2" :value="__('Confirm Password')" />

                    <x-text-input id="password_confirmation" class="block mt-1 w-full bg-white text-azul rounded p-2"
                                    type="password"
                                    name="password_confirmation" required autocomplete="new-password" />

                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <a class="underline text-sm text-beige2 hover:text-beige1 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                        {{ __('Already registered?') }}
                    </a>

                    <button type="submit" class="btn-secondary ml-3 hover:!bg-beige hover:!text-negro">{{ __('Register') }}</button>
                </div>
            </form>
        </div>
    </div>

@endsection

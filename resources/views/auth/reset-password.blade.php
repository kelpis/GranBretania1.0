@extends('layouts.site')

@section('content')

    <div class="container mx-auto px-4">
        <div class="max-w-md mx-auto bg-azul text-beige2 rounded-lg shadow-lg p-8">
            <h2 class="text-2xl font-semibold text-beige2 mb-4">{{ __('Reset Password') }}</h2>

            <form method="POST" action="{{ route('password.store') }}">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email Address -->
                <div>
                    <x-input-label for="email" class="text-beige2" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full bg-white text-azul rounded p-2" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="password" class="text-beige2" :value="__('Password')" />
                    <x-text-input id="password" class="block mt-1 w-full bg-white text-azul rounded p-2" type="password" name="password" required autocomplete="new-password" />
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
                    <button type="submit" class="btn-secondary hover:!bg-beige hover:!text-negro">{{ __('Reset Password') }}</button>
                </div>
            </form>
        </div>
    </div>

@endsection

@extends('layouts.site')

@section('content')

    <div class="container mx-auto px-4">
        <div class="max-w-md mx-auto bg-azul text-beige2 rounded-lg shadow-lg p-8">
            <h2 class="text-2xl font-semibold text-beige2 mb-4">{{ __('Confirm') }}</h2>

            <div class="mb-4 text-sm text-beige2">
                {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
            </div>

            <form method="POST" action="{{ route('password.confirm') }}">
                @csrf

                <!-- Password -->
                <div>
                    <x-input-label for="password" class="text-beige2" :value="__('Password')" />

                    <x-text-input id="password" class="block mt-1 w-full bg-white text-azul rounded p-2"
                                    type="password"
                                    name="password"
                                    required autocomplete="current-password" />

                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="flex justify-end mt-4">
                    <button type="submit" class="btn-secondary hover:!bg-beige hover:!text-negro">{{ __('Confirm') }}</button>
                </div>
            </form>
        </div>
    </div>

@endsection

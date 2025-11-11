    @extends('layouts.site')

    @section('content')

        {{-- Estilo consistente con login/register: contenedor + card azul --}}
        <div class="container mx-auto px-4">
        <div class="max-w-md mx-auto bg-azul text-beige2 rounded-lg shadow-lg p-8 mt-20">
            <h2 class="text-2xl font-semibold text-beige2 mb-4">{{ __('Forgot your password?') }}</h2>

            <div class="mb-4 text-sm text-beige2">
                {{ __('No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <!-- Email Address -->
                <div>
                    <x-input-label for="email" class="text-beige2" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full bg-white text-azul rounded p-2" type="email" name="email" :value="old('email')" required autofocus />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="flex items-center justify-center mt-4">
                    <button type="submit" class="btn-secondary hover:!bg-beige hover:!text-negro">{{ __('Email Password Reset Link') }}</button>
                </div>
            </form>
        </div>
    </div>

@endsection

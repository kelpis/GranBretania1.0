@extends('layouts.site')

@section('content')

    <div class="container mx-auto px-4">
        <div class="max-w-md mx-auto bg-azul text-beige2 rounded-lg shadow-lg p-8">
            <h2 class="text-2xl font-semibold text-beige2 mb-4">{{ __('Verify Email') }}</h2>

            <div class="mb-4 text-sm text-beige2">
                {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="mb-4 font-medium text-sm text-green-600">
                    {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                </div>
            @endif

            <div class="mt-4 flex items-center justify-between">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf

                    <div>
                        <button type="submit" class="btn-secondary hover:!bg-beige hover:!text-negro">{{ __('Resend Verification Email') }}</button>
                    </div>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button type="submit" class="underline text-sm text-beige2 hover:text-beige1 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ __('Log Out') }}
                    </button>
                </form>
            </div>
        </div>
    </div>

@endsection

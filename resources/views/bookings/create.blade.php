@extends('layouts.site')

@section('title', 'Reservar · Gran Bretania')

@section('header')
    @include('layouts.navigation')
@endsection

@section('content')

    <div class="max-w-2xl mx-auto my-12 text-center">
        <h2 class="text-azul text-3xl font-semibold mb-2">Reservar clase</h2>
        <p class="text-gray-700 text-base leading-relaxed mx-auto max-w-xl">Selecciona fecha y hora y rellena tus datos para completar la reserva.</p>
    </div>

    <div class="container mx-auto px-4">
        <div class="max-w-xl mx-auto bg-azul text-beige2 rounded-lg shadow-lg p-8">

            {{-- Mensajes de sesión / errores generales --}}
            <x-auth-session-status class="mb-4" :status="session('ok')" />

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 p-3 mb-4 rounded">
                    <ul class="list-disc pl-5 text-red-600 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="booking-form" method="POST" action="{{ route('bookings.store') }}" class="" data-grecaptcha="v3" data-recaptcha-action="booking">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    {{-- Fecha (full width) --}}
                    <div class="md:col-span-2">
                        <x-input-label for="class_date" class="text-beige2" :value="__('Fecha')" />
                        <select id="class_date" data-availability-url="{{ route('bookings.availability') }}" data-old-date="{{ old('class_date') }}" name="class_date" class="block mt-1 w-full bg-white text-azul rounded p-2" required aria-describedby="date-help">
                            <option value="">— Selecciona fecha —</option>
                        </select>
                        <p id="date-help" class="text-sm text-white/80 mt-1">Solo días laborables (L–V). Los fines de semana no están disponibles.</p>
                        <x-input-error :messages="$errors->get('class_date')" class="mt-2" />
                    </div>

                    {{-- Hora (full width) --}}
                    <div class="md:col-span-2">
                        <x-input-label for="class_time" class="text-beige2" :value="__('Hora')" />
                        <select id="class_time" data-old-time="{{ old('class_time') }}" name="class_time" class="block mt-1 w-full bg-white text-azul rounded p-2" required>
                            <option value="">— Selecciona hora —</option>
                            @foreach (range(9, 21) as $h)
                                @php $hh = str_pad($h, 2, '0', STR_PAD_LEFT) . ':00'; @endphp
                                <option value="{{ $hh }}" @selected(old('class_time') === $hh)>
                                    {{ $hh }}
                                </option>
                            @endforeach
                        </select>
                        <p id="time-help" class="text-sm text-white/80 mt-1"></p>
                        <x-input-error :messages="$errors->get('class_time')" class="mt-2" />
                    </div>

                    {{-- Nombre --}}
                    <div>
                        <x-input-label for="name" class="text-beige2" :value="__('Nombre')" />
                        <x-text-input id="name" class="block mt-1 w-full bg-white text-azul rounded p-2" type="text" name="name" :value="old('name', auth()->user()->name ?? '')" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    {{-- Teléfono --}}
                    <div>
                        <x-input-label for="phone" class="text-beige2" :value="__('Teléfono')" />
                        <x-text-input id="phone" class="block mt-1 w-full bg-white text-azul rounded p-2" type="tel" name="phone" :value="old('phone')" placeholder="(opcional)" />
                        <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                    </div>

                    {{-- Email (full width below) --}}
                    <div class="md:col-span-2">
                        <x-input-label for="email" class="text-beige2" :value="__('Correo electrónico')" />
                        <x-text-input id="email" class="block mt-1 w-full bg-white text-azul rounded p-2" type="email" name="email" :value="old('email', auth()->user()->email ?? '')" required />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    {{-- Comentarios (full width) --}}
                    <div class="md:col-span-2">
                        <x-input-label for="notes" class="text-beige2" :value="__('Comentarios')" />
                        <textarea id="notes" name="notes" class="block mt-1 w-full bg-white text-azul rounded p-2" rows="3" placeholder="Información adicional (opcional)">{{ old('notes') }}</textarea>
                        <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                    </div>

                    {{-- GDPR (full width) --}}
                    <div class="md:col-span-2">
                        <label class="inline-flex items-center text-sm text-beige2">
                            <input type="checkbox" name="gdpr" value="1" required class="rounded border-white/30 text-azul">
                            <span class="ml-2">He leído y acepto la <a href="{{ route('privacy') }}" class="underline">política de protección de datos</a>.</span>
                        </label>
                        <x-input-error :messages="$errors->get('gdpr')" class="mt-2" />
                    </div>

                    {{-- Botón (full width) --}}
                    <div class="md:col-span-2 flex items-center justify-center mt-2">
                        <button type="submit" class="btn-secondary hover:!bg-beige hover:!text-negro">Enviar reserva</button>
                    </div>

                </div>

            </form>

        </div>
    </div>

    <script src="/js/bookings.js"></script>

  
@endsection
@extends('layouts.site')

@section('title', 'Editar reserva · Gran Bretania')

@section('header')
    @include('layouts.navigation')
@endsection

@section('content')

    <div class="max-w-2xl mx-auto my-12 text-center">
        <h2 class="text-azul text-3xl font-semibold mb-2">Editar reserva</h2>
        <p class="text-gray-700 text-base leading-relaxed mx-auto max-w-xl">Modifica fecha, hora o tus datos de contacto.</p>
    </div>

    <div class="container mx-auto px-4">
        <div class="max-w-xl mx-auto bg-azul text-beige2 rounded-lg shadow-lg p-8">

            {{-- Mensajes de sesión / errores generales --}}
            <x-auth-session-status class="mb-4" :status="session('ok')" />

            @if ($errors->has('general'))
                <div class="bg-red-50 border border-red-200 p-3 mb-4 rounded text-red-800">
                    {{ $errors->first('general') }}
                </div>
            @endif

            @if ($errors->any() && !$errors->has('general'))
                <div class="bg-red-50 border border-red-200 p-3 mb-4 rounded">
                    <ul class="list-disc pl-5 text-red-600 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="booking-edit-form" method="POST" action="{{ route('user.bookings.update', $booking) }}" class="">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    {{-- Fecha (full width) --}}
                    <div class="md:col-span-2">
                        <x-input-label for="class_date" class="text-beige2" :value="__('Fecha')" />
                        <select id="class_date" data-availability-url="{{ route('bookings.availability') }}" data-old-date="{{ old('class_date', $booking->class_date) }}" data-except="{{ $booking->id }}" name="class_date" class="block mt-1 w-full bg-white text-azul rounded p-2" required aria-describedby="date-help">
                            <option value="">— Selecciona fecha —</option>
                        </select>
                        <p id="date-help" class="text-sm text-white/80 mt-1">Solo días laborables (L–V). Los fines de semana no están disponibles.</p>
                        @error('class_date')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Hora (full width) --}}
                    <div class="md:col-span-2">
                        <x-input-label for="class_time" class="text-beige2" :value="__('Hora')" />
                        <select id="class_time" data-old-time="{{ old('class_time') ? substr(old('class_time'),0,5) : substr($booking->class_time,0,5) }}" name="class_time" class="block mt-1 w-full bg-white text-azul rounded p-2" required>
                            <option value="">— Selecciona hora —</option>
                            @foreach (range(9, 21) as $h)
                                @php $hh = str_pad($h, 2, '0', STR_PAD_LEFT) . ':00'; @endphp
                                <option value="{{ $hh }}" @selected((old('class_time') ? substr(old('class_time'),0,5) : substr($booking->class_time,0,5)) === $hh)>
                                    {{ $hh }}
                                </option>
                            @endforeach
                        </select>
                        <p id="time-help" class="text-sm text-white/80 mt-1"></p>
                        @error('class_time')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Nombre --}}
                    <div>
                        <x-input-label for="name" class="text-beige2" :value="__('Nombre')" />
                        <x-text-input id="name" class="block mt-1 w-full bg-white text-azul rounded p-2" type="text" name="name" :value="old('name', $booking->name)" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    {{-- Teléfono --}}
                    <div>
                        <x-input-label for="phone" class="text-beige2" :value="__('Teléfono')" />
                        <x-text-input id="phone" class="block mt-1 w-full bg-white text-azul rounded p-2" type="tel" name="phone" :value="old('phone', $booking->phone)" placeholder="(opcional)" />
                        <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                    </div>

                    {{-- Notas (full width) --}}
                    <div class="md:col-span-2">
                        <x-input-label for="notes" class="text-beige2" :value="__('Notas')" />
                        <textarea id="notes" name="notes" class="block mt-1 w-full bg-white text-azul rounded p-2" rows="3">{{ old('notes', $booking->notes) }}</textarea>
                        <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                    </div>

                    {{-- Botón (full width) --}}
                    <div class="md:col-span-2 flex items-center justify-center mt-2">
                        <button type="submit" class="btn-secondary hover:!bg-beige hover:!text-negro">Guardar cambios</button>
                    </div>

                </div>

            </form>

        </div>
    </div>

    <script src="/js/bookings.js"></script>

@endsection

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar reserva</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow sm:rounded-lg">

                {{-- Mensaje de éxito (por si vuelves a esta vista desde redirect con ok) --}}
                @if (session('ok'))
                    <div class="bg-green-50 border border-green-200 text-green-800 p-3 mb-4 rounded">
                        {{ session('ok') }}
                    </div>
                @endif

                {{-- Mensajes de error general (ej: 24h o límite de ediciones) --}}
                @if ($errors->has('general'))
                    <div class="bg-red-50 border border-red-200 p-3 mb-4 rounded text-red-800">
                        {{ $errors->first('general') }}
                    </div>
                @endif

                {{-- Lista de errores por campo --}}
                @if ($errors->any() && !$errors->has('general'))
                    <div class="bg-red-50 border border-red-200 p-3 mb-4 rounded">
                        <ul class="list-disc pl-5 text-red-600 text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="booking-edit-form" method="POST" action="{{ route('user.bookings.update', $booking) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block font-medium">Fecha</label>
                        <input type="date"
                               name="class_date"
                               value="{{ old('class_date', $booking->class_date) }}"
                               data-availability-url="{{ route('bookings.availability') }}"
                               data-except="{{ $booking->id }}"
                               class="w-full border rounded p-2"
                               required>
                        @error('class_date')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium">Hora</label>
                        <select id="class_time"
                                name="class_time"
                                data-old-time="{{ old('class_time', substr($booking->class_time,0,5)) }}"
                                class="w-full border rounded p-2"
                                required>
                            <option value="">— Selecciona hora —</option>
                            @foreach(range(9,21) as $h)
                                @php $hh = str_pad($h, 2, '0', STR_PAD_LEFT) . ':00'; @endphp
                                <option value="{{ $hh }}"
                                    @selected(old('class_time', substr($booking->class_time,0,5)) === $hh)>
                                    {{ $hh }}
                                </option>
                            @endforeach
                        </select>
                        <p id="time-help" class="text-sm text-gray-500 mt-1"></p>
                        @error('class_time')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium">Nombre</label>
                        <input type="text"
                               name="name"
                               value="{{ old('name', $booking->name) }}"
                               class="w-full border rounded p-2"
                               required>
                        @error('name')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium">Teléfono</label>
                        <input type="tel"
                               name="phone"
                               value="{{ old('phone', $booking->phone) }}"
                               class="w-full border rounded p-2">
                        @error('phone')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium">Notas</label>
                        <textarea name="notes" class="w-full border rounded p-2">{{ old('notes', $booking->notes) }}</textarea>
                        @error('notes')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-2">
                        <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            Guardar cambios
                        </button>
                        <a href="{{ route('user.bookings.index') }}" class="text-gray-600 hover:underline">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="/js/user-bookings.js"></script>
</x-app-layout>

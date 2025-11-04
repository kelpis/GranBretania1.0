<x-app-layout>
  <div class="max-w-2xl mx-auto py-8">
    <h1 class="text-2xl font-semibold mb-4 text-center">Reservar una clase</h1>

    {{-- Mostrar errores --}}
    @if ($errors->any())
      <div class="bg-red-50 border border-red-200 p-3 mb-4 rounded">
        <ul class="list-disc pl-5 text-red-600 text-sm">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    {{-- Mensaje de éxito --}}
    @if (session('ok'))
      <div class="bg-green-50 border border-green-200 text-green-700 p-3 mb-4 rounded">
        {{ session('ok') }}
      </div>
    @endif

    {{-- Formulario de reserva --}}
    <form id="booking-form" method="POST" action="{{ route('bookings.store') }}" class="space-y-5" data-grecaptcha="v3" data-recaptcha-action="booking">
      @csrf

      {{-- Fecha y hora --}}
      <div>
        <label class="block text-sm font-medium mb-1">Fecha*</label>
        <select id="class_date" data-availability-url="{{ route('bookings.availability') }}" data-old-date="{{ old('class_date') }}" name="class_date" class="w-full border rounded p-2" required aria-describedby="date-help">
          <option value="">— Selecciona fecha —</option>
          {{-- JS rellenará las próximas fechas (excluyendo fines de semana) --}}
        </select>
        <p id="date-help" class="text-sm text-gray-500 mt-1">Solo días laborables (L–V). Los fines de semana no están disponibles.</p>
        @error('class_date')
          <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror

        <label class="block text-sm font-medium mb-1 mt-4">Hora*</label>
        <select id="class_time" data-old-time="{{ old('class_time') }}" name="class_time" class="w-full border rounded p-2" required>
          <option value="">— Selecciona hora —</option>
          @foreach (range(9, 21) as $h)
            @php $hh = str_pad($h, 2, '0', STR_PAD_LEFT) . ':00'; @endphp
            <option value="{{ $hh }}" @selected(old('class_time') === $hh)>
              {{ $hh }}
            </option>
          @endforeach
        </select>
        <p id="time-help" class="text-sm text-gray-500 mt-1"></p>
        @error('class_time')
          <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
      </div>

      {{-- Nombre --}}
      <div>
        <label class="block text-sm font-medium mb-1">Nombre*</label>
        <input type="text" name="name" value="{{ old('name', auth()->user()->name ?? '') }}" class="w-full border rounded p-2" required>
        @error('name')
          <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
      </div>

      {{-- Email --}}
      <div>
        <label class="block text-sm font-medium mb-1">Correo electrónico*</label>
        <input type="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}" class="w-full border rounded p-2" required>
        @error('email')
          <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
      </div>

      {{-- Teléfono --}}
      <div>
        <label class="block text-sm font-medium mb-1">Teléfono</label>
        <input type="tel" name="phone" value="{{ old('phone') }}" class="w-full border rounded p-2"
          placeholder="(opcional)">
        @error('phone')
          <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
      </div>

      {{-- Comentarios --}}
      <div>
        <label class="block text-sm font-medium mb-1">Comentarios</label>
        <textarea name="notes" class="w-full border rounded p-2" rows="3"
          placeholder="Información adicional (opcional)">{{ old('notes') }}</textarea>
        @error('notes')
          <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
      </div>

      {{-- Botón --}}
      <div>
        <label class="inline-flex items-center text-sm">
          <input type="checkbox" name="gdpr" value="1" required class="border">
          <span class="ml-2">He leído y acepto la <a href="{{ route('privacy') }}" class="text-blue-600 underline">política de protección de datos</a>.</span>
        </label>
      </div>

      <div class="text-center">
        {{-- reCAPTCHA v3: token se inyecta por JS desde layout cuando data-grecaptcha="v3" está presente --}}
        <button type="submit" class="px-5 py-2 bg-blue-600 text-white font-semibold rounded hover:bg-blue-700">
          Enviar reserva
        </button>
      </div>
    </form>
  </div>
      <script src="/js/bookings.js"></script>
</x-app-layout>
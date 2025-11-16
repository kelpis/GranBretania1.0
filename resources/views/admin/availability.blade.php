<x-app-layout>
  

  <div class="py-8 max-w-6xl mx-auto space-y-8 sm:px-6 lg:px-8">

    {{-- NOTIFICACIONES --}}
    @if (session('ok'))
      <div class="p-3 rounded-lg bg-ok/20 border border-ok/40 text-ok shadow-md">
        {{ session('ok') }}
      </div>
    @endif

    @if (session('error'))
      <div class="p-3 rounded-lg bg-rojo/20 border border-rojo/40 text-rojo shadow-md">
        {{ session('error') }}
      </div>
    @endif

    @if (session('generated'))
      <div class="p-4 bg-azul/20 border border-azul/40 rounded-xl shadow-md text-sm">
        <strong class="text-azul">Detalle de franjas generadas:</strong>
        <div class="mt-2 max-h-40 overflow-auto text-xs font-mono bg-white rounded p-2 border border-azul/20">
          @foreach(session('generated') as $g)
            <div>{{ $g }}</div>
          @endforeach
        </div>
      </div>
    @endif

    {{-- ============================= --}}
    {{-- FRANJA PUNTUAL (AZUL FUERTE) --}}
    {{-- ============================= --}}
    <div class="rounded-2xl shadow-xl border border-azul bg-azul/10 p-6 space-y-4">
      <h3 class="font-semibold text-azul text-lg border-b border-azul/30 pb-2">
        Añadir o actualizar franja puntual
      </h3>

      <form method="POST" action="{{ route('admin.availability.store') }}" class="grid md:grid-cols-5 gap-4">
        @csrf

        <input type="date" name="date"
          class="p-2 rounded-lg bg-white border border-azul/50 text-azul shadow-sm focus:ring-azul focus:border-azul"
          required>

        <select name="start_time"
          class="p-2 rounded-lg bg-white border border-azul/50 text-azul shadow-sm focus:ring-azul focus:border-azul"
          required>
          @for($h=0;$h<24;$h++)
            <option>{{ str_pad($h,2,'0',STR_PAD_LEFT) }}:00</option>
          @endfor
        </select>

        <select name="end_time"
          class="p-2 rounded-lg bg-white border border-azul/50 text-azul shadow-sm focus:ring-azul focus:border-azul"
          required>
          @for($h=0;$h<=24;$h++)
            <option>{{ $h===24?'24:00':str_pad($h,2,'0',STR_PAD_LEFT).':00' }}</option>
          @endfor
        </select>

        <select name="status"
          class="p-2 rounded-lg bg-white border border-azul/50 text-azul shadow-sm focus:ring-azul focus:border-azul">
          <option value="available">Disponible</option>
          <option value="blocked">Bloqueado</option>
        </select>

        <button class="px-4 py-2 rounded-full bg-azul text-beige2 hover:bg-rojo transition shadow text-sm">
          Guardar
        </button>
      </form>
    </div>

    {{-- ======================== --}}
    {{-- BLOQUEAR DÍA (ROJO) --}}
    {{-- ======================== --}}
    <div class="rounded-2xl shadow-xl border border-rojo bg-rojo/10 p-6">
      <h3 class="font-semibold text-rojo text-lg border-b border-rojo/30 pb-2">
        Bloquear día completo
      </h3>

      <form method="POST" action="{{ route('admin.availability.store') }}" class="mt-4 flex flex-wrap items-end gap-4">
        @csrf

        <div>
          <label class="block text-xs text-rojo">Fecha</label>
          <input type="date" name="date"
            class="p-2 rounded-lg bg-white border border-rojo/50 text-azul focus:ring-rojo focus:border-rojo shadow-sm"
            required>
        </div>

        <input type="hidden" name="start_time" value="00:00">
        <input type="hidden" name="end_time" value="24:00">
        <input type="hidden" name="status" value="blocked">

        <button class="px-4 py-2 rounded-full bg-rojo text-beige2 hover:bg-red-800 transition shadow text-sm"
          onclick="return confirm('¿Bloquear todo el día?')">
          Bloquear día
        </button>
      </form>
    </div>

    {{-- ================================= --}}
    {{-- GENERAR EN LOTE (AZUL MÁS MARCADO) --}}
    {{-- ================================= --}}
    <div class="rounded-2xl shadow-xl border border-azul bg-azul/10 p-6 space-y-4">
      <h3 class="font-semibold text-azul text-lg border-b border-azul/30 pb-2">
        Generar franjas (lote)
      </h3>

      <form method="POST" action="{{ route('admin.availability.generate') }}" class="grid md:grid-cols-6 gap-4">
        @csrf

        <div>
          <label class="block text-xs text-gray-700">Desde</label>
          <input type="date" name="from_date"
            class="p-2 rounded-lg bg-white border border-azul/50 text-azul shadow-sm focus:ring-azul focus:border-azul"
            required>
        </div>

        <div>
          <label class="block text-xs text-gray-700">Hasta</label>
          <input type="date" name="to_date"
            class="p-2 rounded-lg bg-white border border-azul/50 text-azul shadow-sm focus:ring-azul focus:border-azul"
            required>
        </div>

        <input type="hidden" name="full_day" value="1">

        <div>
          <label class="block text-xs text-gray-700">Estado</label>
          <select name="status"
            class="p-2 rounded-lg bg-white border border-azul/50 text-azul shadow-sm focus:ring-azul focus:border-azul">
            <option value="available">Disponible</option>
            <option value="blocked">Bloqueado</option>
          </select>
        </div>

        <div class="md:col-span-6">
          <button class="px-4 py-2 rounded-full bg-azul text-beige2 hover:bg-rojo transition shadow text-sm">
            Generar
          </button>
        </div>
      </form>
    </div>

    {{-- ======================== --}}
    {{-- TABLA (TOTAL BLUE MODE) --}}
    {{-- ======================== --}}
    <div class="rounded-2xl shadow-xl border border-azul bg-azul/5 p-6">
      <h3 class="font-semibold text-azul text-lg mb-3">Franjas</h3>

      <div class="overflow-x-auto">
        <table class="min-w-full text-sm text-azul">
          <thead class="bg-azul text-beige2 uppercase text-xs tracking-wide">
            <tr>
              <th class="py-3 px-3 text-left">Fecha</th>
              <th class="py-3 px-3 text-left">Inicio</th>
              <th class="py-3 px-3 text-left">Fin</th>
              <th class="py-3 px-3 text-left">Estado</th>
              <th class="py-3 px-3 text-left">Acciones</th>
            </tr>
          </thead>

          <tbody>
            @forelse ($slots as $s)
            <tr class="odd:bg-beige2 even:bg-white hover:bg-azul/10 transition">
              <td class="py-3 px-3">{{ \Carbon\Carbon::parse($s->date)->format('d/m/Y') }}</td>
              <td class="py-3 px-3">{{ substr($s->start_time,0,5) }}</td>
              <td class="py-3 px-3">{{ substr($s->end_time,0,5) }}</td>
              <td class="py-3 px-3">
                <span class="px-3 py-1 rounded-full text-xs font-semibold
                    {{ $s->status === 'available' ? 'bg-ok text-white' : 'bg-gray-600 text-white' }}">
                  {{ $s->status === 'available' ? 'Disponible' : 'Bloqueado' }}
                </span>
              </td>
              <td class="py-3 px-3">
                <div class="flex flex-wrap gap-2">

                  <form method="POST" action="{{ route('admin.availability.toggle', $s) }}">
                    @csrf @method('PATCH')
                    <button class="px-3 py-1 rounded-full bg-info text-negro text-xs font-medium hover:bg-yellow-500 transition">
                      Cambiar
                    </button>
                  </form>

                  <form method="POST" action="{{ route('admin.availability.destroy', $s) }}"
                        onsubmit="return confirm('¿Eliminar esta franja?');">
                    @csrf @method('DELETE')
                    <button class="px-3 py-1 rounded-full bg-rojo text-beige2 text-xs font-medium hover:bg-red-800 transition">
                      Eliminar
                    </button>
                  </form>

                </div>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="5" class="py-4 text-center text-gray-500">No hay franjas creadas.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <div class="mt-4">
        {{ $slots->links() }}
      </div>
    </div>
  </div>
</x-app-layout>

<x-app-layout>

  {{--
    Página: Administración → Franjas de disponibilidad
    Propósito: interfaz para crear/editar/borrar franjas horarias, bloquear días
    Notas:
      - Formularios POST usan rutas en `admin.availability.*`.
      - Usamos inputs ocultos para crear franjas de día completo (00:00–24:00).
      - Las confirmaciones se gestionan con Alpine.js (x-data/x-show) para evitar
        envíos accidentales.
  --}}

  <div class="bg-beige2 dark:bg-slate-950 -mx-4 sm:-mx-6 lg:-mx-8">
  <div class="py-8 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

    {{-- NOTIFICACIONES: mensajes de sesión (ok / error) que muestran resultado de acciones POST --}}
    @if (session('ok'))
      <div class="p-3 rounded-lg bg-ok/20 border border-ok/40 text-ok shadow-md dark:bg-emerald-950 dark:text-emerald-100 dark:border-emerald-500/60">
        {{ session('ok') }}
      </div>
    @endif

    @if (session('error'))
      <div class="p-3 rounded-lg bg-rojo/20 border border-rojo/40 text-rojo shadow-md dark:bg-rose-900 dark:text-rose-100 dark:border-rose-500/70">
        {{ session('error') }}
      </div>
    @endif

    

    
    {{-- FRANJA PUNTUAL: formulario para añadir o actualizar una franja concreta (fecha, inicio, fin, estado) --}}
  
    <div class="rounded-2xl shadow-xl border border-azul bg-azul/10 p-6 space-y-4 dark:bg-slate-700 dark:border-slate-700">
      <h3 class="font-semibold text-azul text-lg border-b border-azul/30 pb-2 dark:text-white">
        Añadir o actualizar franja puntual
      </h3>

      <form method="POST" action="{{ route('admin.availability.store') }}" class="grid md:grid-cols-5 gap-4">
        @csrf

        <input type="date" name="date"
          class="p-2 rounded-lg bg-white border border-azul/50 text-azul shadow-sm focus:ring-azul focus:border-azul dark:bg-slate-800 dark:text-slate-100 dark:border-slate-700"
          required>

        <select name="start_time"
          class="p-2 rounded-lg bg-white border border-azul/50 text-azul shadow-sm focus:ring-azul focus:border-azul dark:bg-slate-800 dark:text-slate-100 dark:border-slate-700"
          required>
          @for($h = 0; $h < 24; $h++)
            <option>{{ str_pad($h, 2, '0', STR_PAD_LEFT) }}:00</option>
          @endfor
        </select>

        <select name="end_time"
          class="p-2 rounded-lg bg-white border border-azul/50 text-azul shadow-sm focus:ring-azul focus:border-azul dark:bg-slate-800 dark:text-slate-100 dark:border-slate-700"
          required>
          @for($h = 0; $h <= 24; $h++)
            <option>{{ $h === 24 ? '24:00' : str_pad($h, 2, '0', STR_PAD_LEFT) . ':00' }}</option>
          @endfor
        </select>

        <select name="status"
          class="p-2 rounded-lg bg-white border border-azul/50 text-azul shadow-sm focus:ring-azul focus:border-azul dark:bg-slate-800 dark:text-slate-100 dark:border-slate-700">
          <option value="available">Disponible</option>
          <option value="blocked">Bloqueado</option>
        </select>

        <button class="px-4 py-2 rounded-full bg-azul text-beige2 hover:bg-rojo transition shadow text-sm">
          Guardar
        </button>
      </form>
    </div>

    {{-- ===================================================== --}}
    {{-- BLOQUEAR DÍA (ROJO): crea una franja 00:00–24:00 con estado 'blocked' --}}
    {{-- Se pide confirmación mediante modal Alpine antes de enviar. --}}
    {{-- ===================================================== --}}
    <div class="rounded-2xl shadow-xl border border-rojo bg-rojo/10 p-6 dark:bg-slate-700 dark:border-slate-700">
      <h3 class="font-semibold text-rojo text-lg border-b border-rojo/30 pb-2">
        Bloquear día completo
      </h3>

      <form x-data="{ showConfirm:false }" x-cloak x-ref="blockForm" method="POST" action="{{ route('admin.availability.store') }}" class="mt-4 flex flex-wrap items-end gap-4">
        @csrf

        <div>
          <label class="block text-xs text-rojo">Fecha</label>
          <input type="date" name="date"
            class="p-2 rounded-lg bg-white border border-rojo/50 text-azul focus:ring-rojo focus:border-rojo shadow-sm dark:bg-slate-800 dark:text-slate-100 dark:border-slate-700"
            required>
        </div>

        <input type="hidden" name="start_time" value="00:00">
        <input type="hidden" name="end_time" value="24:00">
        <input type="hidden" name="status" value="blocked">

        <button type="button" class="px-4 py-2 rounded-full bg-rojo text-beige2 hover:bg-red-800 transition shadow text-sm" @click="showConfirm = true">
          Bloquear día
        </button>

        <!-- Modal de confirmación (Alpine.js) -->
        <div x-show="showConfirm" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center" style="display:none;">
          <div class="absolute inset-0 bg-black/50" @click="showConfirm = false"></div>
          <div class="bg-white rounded-lg shadow-lg p-6 z-10 w-full max-w-md">
            <h4 class="text-lg font-semibold mb-2">Confirmar bloqueo</h4>
            <p class="text-sm text-gray-700 mb-4">¿Estás seguro de que quieres bloquear todo el día seleccionado? Esta acción creará una franja desde 00:00 hasta 24:00 con estado <strong>bloqueado</strong>.</p>
            <div class="flex justify-end gap-3">
              <button type="button" class="px-4 py-2 rounded-full border" @click="showConfirm = false">Cancelar</button>
              <button type="button" class="px-4 py-2 rounded-full bg-rojo text-beige2" @click.prevent="$refs.blockForm.submit(); showConfirm = false">Sí, bloquear</button>
            </div>
          </div>
        </div>
      </form>
    </div>

    
    {{-- GENERAR EN LOTE: generar franjas en rango de fechas--}}
  
    <div class="rounded-2xl shadow-xl border border-azul bg-azul/10 p-6 space-y-4 dark:bg-slate-700 dark:border-slate-700">
      <h3 class="font-semibold text-azul text-lg border-b border-azul/30 pb-2">
        Generar franjas (lote)
      </h3>

      <form method="POST" action="{{ route('admin.availability.generate') }}" class="grid md:grid-cols-5 gap-4">
        @csrf

        <div>
          <label class="block text-xs text-gray-700 dark:text-white">Desde</label>
          <input type="date" name="from_date"
            class="h-10 p-2 md:p-3 rounded-lg bg-white border border-azul/50 text-azul shadow-sm focus:ring-azul focus:border-azul dark:bg-slate-800 dark:text-slate-100 dark:border-slate-700"
            required>
        </div>

        <div>
          <label class="block text-xs text-gray-700 dark:text-white">Hasta</label>
          <input type="date" name="to_date"
            class="h-10 p-2 md:p-3 rounded-lg bg-white border border-azul/50 text-azul shadow-sm focus:ring-azul focus:border-azul dark:bg-slate-800 dark:text-slate-100 dark:border-slate-700"
            required>
        </div>

        <input type="hidden" name="full_day" value="1">

        <div>
          <label class="block text-xs text-gray-700">Estado</label>
          <select name="status"
            class="h-10 p-2 md:p-3 rounded-lg bg-white border border-azul/50 text-azul shadow-sm focus:ring-azul focus:border-azul">
            <option value="available">Disponible</option>
            <option value="blocked">Bloqueado</option>
          </select>

         
        </div>



        <div class="self-end">
          <button
            class="h-10 px-4 rounded-full bg-azul text-beige2 hover:bg-rojo transition shadow text-sm flex items-center justify-center">
            Generar
          </button>
        </div>
      </form>
    </div>

   
    {{-- LISTADO / TABLA: muestra las franjas creadas. En móvil se usan tarjetas y en escritorio una tabla. --}}
   
    <div class="rounded-2xl shadow-xl border border-azul bg-azul/5 p-6 dark:bg-slate-700 dark:border-slate-700">
      <h3 class="font-semibold text-azul text-lg mb-3">Franjas</h3>

      <!-- RESPONSIVE MOVIL: tarjetas (visible en sm and below) -->
      <div class="md:hidden space-y-3">
        @forelse ($slots as $s)
          <div class="bg-white p-4 rounded-lg border shadow-sm dark:bg-slate-900 dark:text-slate-100">
            <div class="flex items-start justify-between">
              <div>
                <div class="text-xs text-gray-500">Fecha</div>
                <div class="font-medium text-sm text-azul">{{ \Carbon\Carbon::parse($s->date)->format('d/m/Y') }}</div>
                <div class="text-xs text-gray-500 mt-2">Hora</div>
                <div class="text-sm text-azul">{{ substr($s->start_time, 0, 5) }} – {{ substr($s->end_time, 0, 5) }}</div>
                <div class="text-xs text-gray-500 mt-2">Estado</div>
                <div class="inline-block mt-1 px-2 py-1 rounded-full text-xs font-semibold {{ $s->status === 'available' ? 'bg-ok text-white' : 'bg-gray-600 text-white' }}">{{ $s->status === 'available' ? 'Disponible' : 'Bloqueado' }}</div>
              </div>

              <div class="flex flex-col items-end gap-2">
                <form method="POST" action="{{ route('admin.availability.toggle', $s) }}">
                  @csrf @method('PATCH')
                  <button class="px-3 py-1 rounded-full bg-info text-negro text-xs font-medium hover:bg-yellow-500 transition">Cambiar</button>
                </form>

                <form method="POST" action="{{ route('admin.availability.destroy', $s) }}" onsubmit="return confirm('¿Eliminar esta franja?');">
                  @csrf @method('DELETE')
                  <button class="px-3 py-1 rounded-full bg-rojo text-beige2 text-xs font-medium hover:bg-red-800 transition">Eliminar</button>
                </form>
              </div>
            </div>
          </div>
        @empty
          <div class="py-4 text-center text-gray-500 dark:text-slate-300">No hay franjas creadas.</div>
        @endforelse
      </div>

      <!-- RESPONSIVE Desktop/tablet: tabla (md+) -->
      <div class="hidden md:block overflow-x-auto">
        <table class="min-w-full text-sm text-azul dark:text-beige2">
          <thead class="bg-azul text-beige2 uppercase text-xs tracking-wide dark:bg-slate-800/90 dark:text-beige2">
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
              <tr class="odd:bg-beige2 even:bg-white hover:bg-azul/10 transition dark:odd:bg-slate-800 dark:even:bg-slate-900 dark:hover:bg-slate-700">
                <td class="py-3 px-3">{{ \Carbon\Carbon::parse($s->date)->format('d/m/Y') }}</td>
                <td class="py-3 px-3">{{ substr($s->start_time, 0, 5) }}</td>
                <td class="py-3 px-3">{{ substr($s->end_time, 0, 5) }}</td>
                <td class="py-3 px-3">
                  <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $s->status === 'available' ? 'bg-ok text-white' : 'bg-gray-600 text-white' }}">
                    {{ $s->status === 'available' ? 'Disponible' : 'Bloqueado' }}
                  </span>
                </td>
                <td class="py-3 px-3">
                  <div class="flex flex-wrap gap-2">

                    <form method="POST" action="{{ route('admin.availability.toggle', $s) }}">
                      @csrf @method('PATCH')
                      <button class="px-3 py-1 rounded-full bg-info text-negro text-xs font-medium hover:bg-yellow-500 transition">Cambiar</button>
                    </form>

                    <form method="POST" action="{{ route('admin.availability.destroy', $s) }}" onsubmit="return confirm('¿Eliminar esta franja?');">
                      @csrf @method('DELETE')
                      <button class="px-3 py-1 rounded-full bg-rojo text-beige2 text-xs font-medium hover:bg-red-800 transition">Eliminar</button>
                    </form>

                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="py-4 text-center text-gray-500 dark:text-slate-300">No hay franjas creadas.</td>
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
  </div>
</x-app-layout>
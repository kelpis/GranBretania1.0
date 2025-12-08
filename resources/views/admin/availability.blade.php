<x-app-layout>

  {{--Página: Admin:Franjas de disponibilidad--}}

  <div class="bg-beige2 dark:bg-slate-950 -mx-4 sm:-mx-6 lg:-mx-8">
    <div class="py-8 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

      {{-- NOTIFICACIONES: mensajes de sesión (ok / error) que muestran resultado de acciones POST --}}
      @if (session('ok'))
        <div
          class="p-3 rounded-lg bg-ok/20 border border-ok/40 text-ok shadow-md dark:bg-emerald-950 dark:text-emerald-100 dark:border-emerald-500/60">
          {{ session('ok') }}
        </div>
      @endif

      @if (session('error'))
        <div
          class="p-3 rounded-lg bg-rojo/20 border border-rojo/40 text-rojo shadow-md dark:bg-rose-900 dark:text-rose-100 dark:border-rose-500/70">
          {{ session('error') }}
        </div>
      @endif




      {{-- FRANJA PUNTUAL: formulario para añadir o actualizar una franja concreta (fecha, inicio, fin, estado) --}}

      <div
        class="rounded-2xl shadow-xl border border-azul bg-azul/10 p-6 space-y-4 dark:bg-slate-700 dark:border-slate-700">
        <h3 class="font-semibold text-azul text-lg border-b border-azul/30 pb-2 dark:text-white">
          Añadir o actualizar franja puntual
        </h3>

        {{-- Formulario con campos: fecha, hora inicio, hora fin, estado --}}
        {{-- Envía POST a admin.availability.store --}}
        <form method="POST" action="{{ route('admin.availability.store') }}" class="grid md:grid-cols-5 gap-4">
          @csrf

          {{-- Campo fecha: obligatorio, tipo date --}}
          <input type="date" name="date"
            class="p-2 rounded-lg bg-white border border-azul/50 text-azul shadow-sm focus:ring-azul focus:border-azul dark:bg-slate-800 dark:text-slate-100 dark:border-slate-700"
            required>

          {{-- Select hora inicio: opciones de 00:00 a 23:00 --}}
          <select name="start_time"
            class="p-2 rounded-lg bg-white border border-azul/50 text-azul shadow-sm focus:ring-azul focus:border-azul dark:bg-slate-800 dark:text-slate-100 dark:border-slate-700"
            required>
            @for($h = 0; $h < 24; $h++)
              <option>{{ str_pad($h, 2, '0', STR_PAD_LEFT) }}:00</option>
            @endfor
          </select>

          {{-- Select hora fin: opciones de 00:00 a 24:00 --}}
          <select name="end_time"
            class="p-2 rounded-lg bg-white border border-azul/50 text-azul shadow-sm focus:ring-azul focus:border-azul dark:bg-slate-800 dark:text-slate-100 dark:border-slate-700"
            required>
            @for($h = 0; $h <= 24; $h++)
              <option>{{ $h === 24 ? '24:00' : str_pad($h, 2, '0', STR_PAD_LEFT) . ':00' }}</option>
            @endfor
          </select>

          {{-- Select estado: disponible o bloqueado --}}
          <select name="status"
            class="p-2 rounded-lg bg-white border border-azul/50 text-azul shadow-sm focus:ring-azul focus:border-azul dark:bg-slate-800 dark:text-slate-100 dark:border-slate-700">
            <option value="available">Disponible</option>
            <option value="blocked">Bloqueado</option>
          </select>

          {{-- Botón guardar: envía el formulario --}}
          <button class="px-4 py-2 rounded-full bg-azul text-beige2 hover:bg-rojo transition shadow text-sm">
            Guardar
          </button>
        </form>
      </div>


      {{-- BLOQUEAR DÍA (ROJO): crea una franja 00:00–24:00 con estado 'blocked' --}}
      {{-- Se pide confirmación mediante modal Alpine antes de enviar. --}}
      <div class="rounded-2xl shadow-xl border border-rojo bg-rojo/10 p-6 dark:bg-slate-700 dark:border-slate-700">
        <h3 class="font-semibold text-rojo text-lg border-b border-rojo/30 pb-2 dark:text-white">
          Bloquear día completo
        </h3>

        {{-- Formulario con campo fecha, campos ocultos para horas y estado --}}
        {{-- Usa Alpine.js para modal de confirmación --}}
        <form x-data="{ showConfirm:false }" x-cloak x-ref="blockForm" method="POST"
          action="{{ route('admin.availability.store') }}" class="mt-4 flex flex-wrap items-end gap-4">
          @csrf

          {{-- Campo fecha para bloquear --}}
          <div>
            <label class="block text-xs text-rojo">Fecha</label>
            <input type="date" name="date"
              class="p-2 rounded-lg bg-white border border-rojo/50 text-azul focus:ring-rojo focus:border-rojo shadow-sm dark:bg-slate-800 dark:text-slate-100 dark:border-slate-700"
              required>
          </div>

          {{-- Campos ocultos: inicio 00:00, fin 24:00, estado blocked --}}
          <input type="hidden" name="start_time" value="00:00">
          <input type="hidden" name="end_time" value="24:00">
          <input type="hidden" name="status" value="blocked">

          {{-- Botón que muestra modal de confirmación --}}
          <button type="button"
            class="px-4 py-2 rounded-full bg-rojo text-beige2 hover:bg-red-800 transition shadow text-sm"
            @click="showConfirm = true">
            Bloquear día
          </button>

          {{-- Modal de confirmación usando Alpine.js --}}
          <div x-show="showConfirm" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center"
            style="display:none;">
            <div class="absolute inset-0 bg-black/50" @click="showConfirm = false"></div>
            <div class="bg-white rounded-lg shadow-lg p-6 z-10 w-full max-w-md dark:bg-slate-800 dark:text-slate-100">
              <h4 class="text-lg font-semibold mb-2">Confirmar bloqueo</h4>
              <p class="text-sm text-gray-700 dark:text-slate-300 mb-4">¿Estás seguro de que quieres bloquear todo el
                día seleccionado? Esta acción creará una franja desde 00:00 hasta 24:00 con estado
                <strong>bloqueado</strong>.
              </p>
              <div class="flex justify-end gap-3">
                <button type="button" class="px-4 py-2 rounded-full border dark:border-slate-600 dark:text-slate-200"
                  @click="showConfirm = false">Cancelar</button>
                <button type="button" class="px-4 py-2 rounded-full bg-rojo text-beige2 dark:bg-red-600"
                  @click.prevent="$refs.blockForm.submit(); showConfirm = false">Sí, bloquear</button>
              </div>
            </div>
          </div>
        </form>
      </div>


      {{-- GENERAR EN LOTE: generar franjas en rango de fechas--}}

      <div
        class="rounded-2xl shadow-xl border border-azul bg-azul/10 p-6 space-y-4 dark:bg-slate-700 dark:border-slate-700">
        <h3 class="font-semibold text-azul text-lg border-b border-azul/30 pb-2 dark:text-white">
          Generar franjas (lote)
        </h3>

        {{-- Formulario con campos: desde, hasta, estado --}}
        {{-- Envía POST a admin.availability.generate --}}
        <form method="POST" action="{{ route('admin.availability.generate') }}" class="grid md:grid-cols-5 gap-4">
          @csrf

          {{-- Campo fecha desde --}}
          <div>
            <label class="block text-xs text-gray-700 dark:text-white">Desde</label>
            <input type="date" name="from_date"
              class="h-10 p-2 md:p-3 rounded-lg bg-white border border-azul/50 text-azul shadow-sm focus:ring-azul focus:border-azul dark:bg-slate-800 dark:text-slate-100 dark:border-slate-700"
              required>
          </div>

          {{-- Campo fecha hasta --}}
          <div>
            <label class="block text-xs text-gray-700 dark:text-white">Hasta</label>
            <input type="date" name="to_date"
              class="h-10 p-2 md:p-3 rounded-lg bg-white border border-azul/50 text-azul shadow-sm focus:ring-azul focus:border-azul dark:bg-slate-800 dark:text-slate-100 dark:border-slate-700"
              required>
          </div>

          {{-- Campo oculto para día completo --}}
          <input type="hidden" name="full_day" value="1">

          {{-- Select estado --}}
          <div>


            <label class="block text-xs text-gray-700">Estado</label>
            <select name="status"
              class="h-10 p-2 rounded-lg bg-white border border-azul/50 text-azul shadow-sm focus:ring-azul focus:border-azul dark:bg-slate-800 dark:text-slate-100 dark:border-slate-700">
              <option value="available">Disponible</option>
              <option value="blocked">Bloqueado</option>
            </select>


          </div>

          {{-- Botón generar --}}
          <div class="self-end">
            <button
              class="h-10 px-4 rounded-full bg-azul text-beige2 hover:bg-rojo transition shadow text-sm flex items-center justify-center">
              Generar
            </button>
          </div>
        </form>
      </div>


      {{-- LISTADO / TABLA: muestra las franjas creadas. En móvil se usan tarjetas y en escritorio una tabla. --}}
      {{-- Incluye acciones: cambiar estado, eliminar con modal de confirmación --}}
      <div class="rounded-2xl shadow-xl border border-azul bg-azul/5 p-6 dark:bg-slate-700 dark:border-slate-700">
        <h3 class="font-semibold text-azul text-lg mb-3 dark:text-white">Franjas</h3>



        {{-- RESPONSIVE MOVIL: tarjetas (visible en sm) --}}
        {{-- Cada tarjeta muestra fecha, hora, estado y botones de acción --}}
        <div class="md:hidden space-y-3">
          @forelse ($slots as $s)
            <div class="bg-white p-4 rounded-lg border shadow-sm dark:bg-slate-900 dark:text-slate-100">
              <div class="flex items-start justify-between">
                <div>
                  <div class="text-xs text-gray-500">Fecha</div>
                  <div class="font-medium text-sm text-azul">{{ \Carbon\Carbon::parse($s->date)->format('d/m/Y') }}</div>
                  <div class="text-xs text-gray-500 mt-2">Hora</div>
                  <div class="text-sm text-azul">{{ substr($s->start_time, 0, 5) }} – {{ substr($s->end_time, 0, 5) }}
                  </div>
                  <div class="text-xs text-gray-500 mt-2">Estado</div>
                  <div
                    class="inline-block mt-1 px-2 py-1 rounded-full text-xs font-semibold {{ $s->status === 'available' ? 'bg-ok text-white' : 'bg-gray-600 text-white' }}">
                    {{ $s->status === 'available' ? 'Disponible' : 'Bloqueado' }}
                  </div>
                </div>

                {{-- Botones de acción: cambiar y eliminar --}}
                <div class="flex flex-col items-end gap-2" x-data="{ showDelete:false }">
                  {{-- Formulario para cambiar estado --}}
                  <form method="POST" action="{{ route('admin.availability.toggle', $s) }}">
                    @csrf @method('PATCH')
                    <button
                      class="px-3 py-1 rounded-full bg-info text-negro text-xs font-medium hover:bg-yellow-500 transition">Cambiar</button>
                  </form>

                  {{-- Formulario para eliminar con modal --}}
                  <form method="POST" action="{{ route('admin.availability.destroy', $s) }}" x-ref="deleteForm">
                    @csrf @method('DELETE')
                    <button type="button" @click="showDelete = true"
                      class="px-3 py-1 rounded-full bg-rojo text-beige2 text-xs font-medium hover:bg-red-800 transition">Eliminar</button>
                  </form>

                  {{-- Modal de confirmación para eliminar --}}
                  <div x-show="showDelete" x-transition.opacity
                    class="fixed inset-0 z-50 flex items-center justify-center" style="display:none;">
                    <div class="absolute inset-0 bg-black/50" @click="showDelete = false"></div>
                    <div
                      class="bg-white rounded-lg shadow-lg p-6 z-10 w-full max-w-md dark:bg-slate-800 dark:text-slate-100">
                      <h4 class="text-lg font-semibold mb-2">Confirmar eliminación</h4>
                      <p class="text-sm text-gray-700 dark:text-slate-300 mb-4">¿Estás seguro de que quieres eliminar esta
                        franja? Esta acción no se puede deshacer.</p>
                      <div class="flex justify-end gap-3">
                        <button type="button"
                          class="px-4 py-2 rounded-full border dark:border-slate-600 dark:text-slate-200"
                          @click="showDelete = false">Cancelar</button>
                        <button type="button" class="px-4 py-2 rounded-full bg-rojo text-beige2 dark:bg-red-600"
                          @click.prevent="$refs.deleteForm.submit(); showDelete = false">Sí, eliminar</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          @empty
            <div class="py-4 text-center text-gray-500 dark:text-slate-300">No hay franjas creadas.</div>
          @endforelse
        </div>

        
        {{-- RESPONSIVE Desktop/tablet: tabla (md+) --}}

        {{-- Tabla con columnas: fecha, inicio, fin, estado, acciones --}}
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
                <tr
                  class="odd:bg-beige2 even:bg-white hover:bg-azul/10 transition dark:odd:bg-slate-800 dark:even:bg-slate-900 dark:hover:bg-slate-700">
                  {{-- Columna fecha --}}
                  <td class="py-3 px-3">{{ \Carbon\Carbon::parse($s->date)->format('d/m/Y') }}</td>
                  {{-- Columna hora inicio --}}
                  <td class="py-3 px-3">{{ substr($s->start_time, 0, 5) }}</td>
                  {{-- Columna hora fin --}}
                  <td class="py-3 px-3">{{ substr($s->end_time, 0, 5) }}</td>
                  {{-- Columna estado con badge --}}
                  <td class="py-3 px-3">
                    <span
                      class="px-3 py-1 rounded-full text-xs font-semibold {{ $s->status === 'available' ? 'bg-ok text-white' : 'bg-gray-600 text-white' }}">
                      {{ $s->status === 'available' ? 'Disponible' : 'Bloqueado' }}
                    </span>
                  </td>
                  {{-- Columna acciones --}}
                  <td class="py-3 px-3">
                    <div class="flex flex-wrap gap-2">

                      {{-- Modal para eliminar por fila --}}
                      <div x-data="{ showDelete:false }" class="flex items-center gap-2">
                        {{-- Formulario cambiar estado --}}
                        <form method="POST" action="{{ route('admin.availability.toggle', $s) }}">
                          @csrf @method('PATCH')
                          <button
                            class="px-3 py-1 rounded-full bg-info text-negro text-xs font-medium hover:bg-yellow-500 transition">Cambiar</button>
                        </form>

                        {{-- Formulario eliminar --}}
                        <form method="POST" action="{{ route('admin.availability.destroy', $s) }}" x-ref="deleteForm">
                          @csrf @method('DELETE')
                          <button type="button" @click="showDelete = true"
                            class="px-3 py-1 rounded-full bg-rojo text-beige2 text-xs font-medium hover:bg-red-800 transition">Eliminar</button>
                        </form>

                        {{-- Modal de confirmación --}}
                        <div x-show="showDelete" x-transition.opacity
                          class="fixed inset-0 z-50 flex items-center justify-center" style="display:none;">
                          <div class="absolute inset-0 bg-black/50" @click="showDelete = false"></div>
                          <div
                            class="bg-white rounded-lg shadow-lg p-6 z-10 w-full max-w-md dark:bg-slate-800 dark:text-slate-100">
                            <h4 class="text-lg font-semibold mb-2">Confirmar eliminación</h4>
                            <p class="text-sm text-gray-700 dark:text-slate-300 mb-4">¿Estás seguro de que quieres
                              eliminar esta franja? Esta acción no se puede deshacer.</p>
                            <div class="flex justify-end gap-3">
                              <button type="button"
                                class="px-4 py-2 rounded-full border dark:border-slate-600 dark:text-slate-200"
                                @click="showDelete = false">Cancelar</button>
                              <button type="button" class="px-4 py-2 rounded-full bg-rojo text-beige2 dark:bg-red-600"
                                @click.prevent="$refs.deleteForm.submit(); showDelete = false">Sí, eliminar</button>
                            </div>
                          </div>
                        </div>
                      </div>

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

        {{-- Paginación --}}
        <div class="mt-4">
          {{ $slots->links() }}
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
<x-app-layout>
    
    <div class="py-8 max-w-6xl mx-auto space-y-16">

        {{-- Mensajes de estado --}}
        @if (session('ok'))
            <div class="p-3 rounded-xl bg-ok/10 border border-ok/40 text-ok text-sm">
                {{ session('ok') }}
            </div>
        @endif

        @if (session('error'))
            <div class="p-3 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm">
                {{ session('error') }}
            </div>
        @endif

        {{-- PENDIENTES DE CONFIRMAR --}}
        <div class="rounded-2xl shadow-xl overflow-hidden border border-beige bg-beige2">
            <div class="bg-azul text-beige2 px-6 py-4 flex items-center justify-between">
                <h3 class="font-semibold text-lg">Reservas pendientes de confirmar</h3>
                <span class="text-xs uppercase tracking-wide text-beige2/80">
                    Panel de administración
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-beige/80 text-azul uppercase text-xs tracking-wider">
                        <tr>
                            <th class="py-3 px-4 text-left">Fecha</th>
                            <th class="py-3 px-4 text-left">Hora</th>
                            <th class="py-3 px-4 text-left">Nombre</th>
                            <th class="py-3 px-4 text-left">Email</th>
                            <th class="py-3 px-4 text-left">Notas</th>
                            <th class="py-3 px-4 text-left">Estado</th>
                            <th class="py-3 px-4 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pendientes as $b)
                            <tr class="odd:bg-beige2 even:bg-white hover:bg-azul/5 transition">
                                <td class="py-3 px-4">
                                    {{ \Carbon\Carbon::parse($b->class_date)->format('d/m/Y') }}
                                </td>
                                <td class="py-3 px-4">
                                    {{ substr($b->class_time, 0, 5) }}
                                </td>
                                <td class="py-3 px-4">
                                    {{ $b->name }}
                                </td>
                                <td class="py-3 px-4">
                                    <a href="mailto:{{ $b->email }}" class="underline text-azul hover:text-rojo">
                                        {{ $b->email }}
                                    </a>
                                </td>
                                <td class="py-3 px-4">
                                    {{ $b->notes }}
                                </td>
                                <td class="py-3 px-4">
                                    @if($b->paid)
                                        <span class="inline-block px-2.5 py-1 text-xs rounded-full bg-ok text-white font-semibold">
                                            Pagada
                                        </span>
                                    @endif
                                    @if(!empty($b->refunded))
                                        <span class="inline-block px-2.5 py-1 text-xs rounded-full bg-info text-negro font-semibold">
                                            Devuelta
                                        </span>
                                    @endif
                                </td>

                                <td class="py-3 px-4">
                                    <div class="flex flex-col gap-2 items-end">

                                        {{-- Confirmar (envía email con link de reunión) --}}
                                        <form method="POST" action="{{ route('admin.bookings.confirm', $b) }}">
                                            @csrf
                                            @method('PATCH')
                                            <div class="flex flex-wrap items-center gap-2">
                                                <input name="meeting_url" type="url"
                                                    placeholder="https://meet.google.com/xxx-xxxx-xxx"
                                                    value="{{ $b->meeting_url ?? '' }}"
                                                    class="px-2 py-1 border border-beige rounded-lg text-sm w-64 focus:ring-azul focus:border-azul"
                                                    aria-label="URL videollamada" />
                                                <button type="submit"
                                                    class="px-3 py-1 rounded-full bg-azul text-beige2 text-xs font-medium hover:bg-rojo transition focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-azul">
                                                    Confirmar
                                                </button>
                                            </div>
                                        </form>

                                        {{-- Cancelar y devolver --}}
                                        @php
                                            $fechaHoraClase = \Carbon\Carbon::parse($b->class_date.' '.substr($b->class_time,0,5));
                                        @endphp
                                        @if($b->paid && !$b->refunded && $fechaHoraClase->isFuture())
                                            <form method="POST" action="{{ route('admin.bookings.refund', $b) }}">
                                                @csrf
                                                <button type="submit"
                                                    onclick="return confirm('¿Devolver el pago y cancelar esta reserva?');"
                                                    class="px-3 py-1 rounded-full bg-rojo text-beige2 text-xs font-medium hover:bg-red-800 transition">
                                                    Cancelar y devolver
                                                </button>
                                            </form>
                                        @endif

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-4 px-4 text-gray-500 text-center">
                                    No hay reservas nuevas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- YA CONFIRMADAS --}}
        <div class="rounded-2xl shadow-xl overflow-hidden border border-beige bg-beige2">
            <div class="bg-azul text-beige2 px-6 py-4">
                <h3 class="font-semibold text-lg">Reservas confirmadas</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-beige/80 text-azul uppercase text-xs tracking-wider">
                        <tr>
                            <th class="py-3 px-4 text-left">Fecha</th>
                            <th class="py-3 px-4 text-left">Hora</th>
                            <th class="py-3 px-4 text-left">Nombre</th>
                            <th class="py-3 px-4 text-left">Email</th>
                            <th class="py-3 px-4 text-left">Enlace</th>
                            <th class="py-3 px-4 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            // Filtra las confirmadas por estado si $confirmadas es una colección
                            $ya_confirmadas = isset($confirmadas) && method_exists($confirmadas, 'where')
                                ? $confirmadas->where('status', 'confirmed')
                                : (array_filter($confirmadas ?? [], fn($x) => (isset($x->status) ? $x->status === 'confirmed' : false)) );
                        @endphp

                        @forelse ($ya_confirmadas as $b)
                            <tr class="odd:bg-beige2 even:bg-white hover:bg-azul/5 transition">
                                <td class="py-3 px-4">
                                    {{ \Carbon\Carbon::parse($b->class_date)->format('d/m/Y') }}
                                </td>
                                <td class="py-3 px-4">
                                    {{ substr($b->class_time, 0, 5) }}
                                </td>
                                <td class="py-3 px-4">
                                    {{ $b->name }}
                                </td>
                                <td class="py-3 px-4">
                                    <a href="mailto:{{ $b->email }}" class="underline text-azul hover:text-rojo">
                                        {{ $b->email }}
                                    </a>
                                </td>
                                <td class="py-3 px-4">
                                    @if(!empty($b->meeting_url))
                                        <a href="{{ route('bookings.join', $b) }}"
                                           target="_blank"
                                           class="underline text-azul hover:text-rojo break-all text-xs">
                                            {{ $b->meeting_url }}
                                        </a>
                                    @else
                                        <span class="text-gray-500 text-sm">Sin enlace</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    @php
                                        $fechaHoraClase = \Carbon\Carbon::parse($b->class_date.' '.substr($b->class_time,0,5));
                                    @endphp
                                    @if($b->paid && !$b->refunded && $fechaHoraClase->isFuture())
                                        <form method="POST" action="{{ route('admin.bookings.refund', $b) }}">
                                            @csrf
                                            <button type="submit"
                                                    onclick="return confirm('¿Devolver el pago y cancelar esta reserva?');"
                                                    class="px-3 py-1 rounded-full bg-rojo text-beige2 text-xs font-medium hover:bg-red-800 transition">
                                                Cancelar y devolver
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-gray-500 text-xs">No aplicable</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-4 px-4 text-gray-500 text-center">
                                    No hay reservas ya confirmadas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- CANCELADAS RECIENTES --}}
        <div class="rounded-2xl shadow-xl overflow-hidden border border-beige bg-beige2">
            <div class="bg-azul text-beige2 px-6 py-4">
                <h3 class="font-semibold text-lg">Canceladas recientes</h3>
            </div>

            <div class="bg-white px-6 py-4">
                <ul class="list-disc pl-6 text-sm space-y-1">
                    @forelse ($canceladas->take(5) as $b)
                        <li>
                            {{ \Carbon\Carbon::parse($b->class_date)->format('d/m/Y') }}
                            {{ substr($b->class_time, 0, 5) }} — {{ $b->name }} ({{ $b->email }})
                            @if(!empty($b->refunded))
                                <span class="ml-2 px-2 py-0.5 text-xs rounded-full bg-info text-negro font-semibold">
                                    Devuelta
                                </span>
                            @endif
                        </li>
                    @empty
                        <li class="text-gray-500">
                            Sin canceladas recientes.
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>

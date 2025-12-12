{{--
    Vista: user/bookings/index.blade.php
    Lista de reservas del usuario, separa próximas de historial y permite acciones (editar, cancelar, unirse).
--}}

@extends('layouts.site')

@section('title', 'Mis clases · Gran Bretania')

@section('header')
    @include('layouts.navigation')
@endsection

@section('content')

@vite(['resources/js/user-bookings.js', 'resources/js/user-bookings-modal.js'])

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if(session('ok'))
                <div class="bg-green-100 text-green-800 p-3 rounded">{{ session('ok') }}</div>
            @endif

            @php
                $hasUpcoming = isset($upcoming) && $upcoming->isNotEmpty();
                $hasHistory = isset($history) && $history->isNotEmpty();
            @endphp

            {{-- Si no hay reservas--}}
            @if(!$hasUpcoming && !$hasHistory)
                <div class="bg-white p-6 shadow sm:rounded-lg">No tienes reservas.</div>
            @else

              
                {{-- PRÓXIMAS CLASES: tabla con acciones (editar, cancelar, unirse) --}}
            
                @if($hasUpcoming)
                    <div class="rounded-2xl shadow-xl overflow-hidden border border-beige">

                        {{-- CABECERA --}}
                        <div class="bg-azul text-beige2 px-6 py-4">
                            <h2 class="font-semibold text-xl leading-tight">Mis clases (próximas)</h2>
                        </div>

                        {{-- Movil: tarjetas para próximas clases--}}
                        <div class="md:hidden space-y-3 p-4">
                            @foreach ($upcoming as $b)
                                @php
                                    $status = $b->status;
                                    $statusLabel = __('statuses.' . $status);
                                    $badge = match ($status) {
                                        'confirmed' => 'bg-ok text-white',
                                        'pending' => 'bg-info text-negro',
                                        'cancelled' => 'bg-rojo text-beige2',
                                        default => 'bg-gray-200 text-negro'
                                    };
                                    $start = \Carbon\Carbon::parse($b->class_date . ' ' . substr($b->class_time, 0, 5));
                                    $hoursUntil = now()->diffInHours($start, false);
                                    $hasEditsLeft = (($b->edit_count ?? 0) < 2);
                                    $isEditable = ($hoursUntil >= 24) && $hasEditsLeft;
                                    $startCancel = \Carbon\Carbon::parse($b->class_date . ' ' . substr($b->class_time, 0, 5));
                                    $hoursUntilCancel = now()->diffInHours($startCancel, false);
                                    $isRefundable = ($hoursUntilCancel >= 24 && $b->paid && !empty($b->payment_intent));
                                @endphp

                                <div class="bg-white p-4 rounded-lg border shadow-sm dark:bg-slate-900 dark:text-white">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <div class="text-xs text-gray-500 dark:text-white">Fecha</div>
                                            <div class="font-medium text-sm text-azul dark:text-white">{{ \Carbon\Carbon::parse($b->class_date)->format('d/m/Y') }}</div>
                                            <div class="text-xs text-gray-500 mt-2 dark:text-white">Hora</div>
                                            <div class="text-sm text-azul dark:text-white">{{ substr($b->class_time, 0, 5) }}</div>
                                            <div class="text-xs text-gray-500 mt-2 dark:text-white">Estado</div>
                                            <div class="inline-block mt-1 px-2 py-1 rounded-full text-xs font-semibold {{ $badge }}">{{ $statusLabel }}</div>
                                        </div>

                                        <div class="flex flex-col items-end gap-2">
                                            {{-- Acciones disponibles: editar (si editable), cancelar (si no pasada), unirse (si confirmada y futura) --}}
                                            <div class="flex flex-wrap gap-2">
                                                @if($b->status !== 'cancelled')
                                                @if($isEditable)
                                                    <a href="{{ route('user.bookings.edit', $b) }}" class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-white border border-azul text-negro hover:bg-azul hover:text-beige2 dark:hover:text-white transition dark:text-negro">✏️ Editar</a>
                                                @else
                                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-400 border border-gray-300 cursor-not-allowed">✏️ Editar</span>
                                                    @endif

                                                    @if($hoursUntil < 0)
                                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-white border border-gray-300 text-gray-400 cursor-not-allowed opacity-60">❌ Cancelar</span>
                                                    @else
                                                        <form method="POST" action="{{ route('user.bookings.destroy', $b) }}" class="inline-block">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" onclick="openCancelModal('{{ route('user.bookings.destroy', $b) }}', '{{ $isRefundable ? '1' : '0' }}')" class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-white border border-rojo text-negro hover:bg-rojo hover:text-beige2 transition dark:text-negro">❌ Cancelar</button>
                                                        </form>
                                                    @endif

                                                    @if($b->status === 'confirmed' && !empty($b->meeting_url))
                                                        @if(isset($start) && $start->isFuture())
                                                            <a href="{{ route('bookings.join', $b) }}" target="_blank" class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-white border border-ok text-negro hover:bg-ok hover:text-beige2 transition dark:text-negro">▶️ Unirse</a>
                                                        @else
                                                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-white border border-gray-300 text-gray-400 cursor-not-allowed opacity-60">▶️ Unirse</span>
                                                        @endif
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>


                        {{-- Desktop: tabla  --}}

                        <div class="hidden md:block overflow-x-auto">
                        <table class="w-full table-fixed text-sm">
                            <colgroup>
                                <col style="width:20%"> <!-- Fecha -->
                                <col style="width:20%"> <!-- Hora -->
                                <col style="width:20%"> <!-- Estado -->
                                <col style="width:40%"> <!-- Acciones -->
                            </colgroup>
                            <thead class="bg-beige/80 text-azul uppercase text-xs tracking-wider">
                                <tr>
                                    <th class="py-3 px-4 text-left">Fecha</th>
                                    <th class="py-3 px-4 text-left">Hora</th>
                                    <th class="py-3 px-4 text-left">Estado</th>
                                    <th class="py-3 px-4 text-left">Acciones</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($upcoming as $b)
                                    @php
                                        // BADGES corporativos
                                        $status = $b->status;
                                        $statusLabel = __('statuses.' . $status);

                                        $badge = match ($status) {
                                            'confirmed' => 'bg-ok text-white',
                                            'pending' => 'bg-info text-negro',
                                            'cancelled' => 'bg-rojo text-beige2',
                                            default => 'bg-gray-200 text-negro'
                                        };

                                        // Cálculos de reglas cancelacion y edicion
                                        $start = \Carbon\Carbon::parse($b->class_date . ' ' . substr($b->class_time, 0, 5));
                                        $hoursUntil = now()->diffInHours($start, false);
                                        $hasEditsLeft = (($b->edit_count ?? 0) < 2);
                                        $isEditable = ($hoursUntil >= 24) && $hasEditsLeft;

                                        $reasons = [];
                                        if ($hoursUntil < 0)
                                            $reasons[] = 'La clase ya ha pasado';
                                        elseif ($hoursUntil < 24)
                                            $reasons[] = 'No puedes editar con menos de 24h';

                                        if (!$hasEditsLeft)
                                            $reasons[] = 'Máximo de ediciones alcanzado';

                                        $reasonText = implode('. ', $reasons) . '.';

                                        $startCancel = \Carbon\Carbon::parse($b->class_date . ' ' . substr($b->class_time, 0, 5));
                                        $hoursUntilCancel = now()->diffInHours($startCancel, false);
                                        $isRefundable = ($hoursUntilCancel >= 24 && $b->paid && !empty($b->payment_intent));
                                    @endphp

                                    {{-- FILAS ZEBRA + HOVER CORPORATIVO --}}
                                    <tr
                                        class="odd:bg-beige2 even:bg-white hover:bg-azul/5 transition dark:odd:bg-slate-800 dark:even:bg-slate-900 dark:hover:bg-slate-700">

                                        <td class="py-3 px-4 font-medium text-negro dark:text-white">
                                            {{ \Carbon\Carbon::parse($b->class_date)->format('d/m/Y') }}
                                        </td>

                                        <td class="py-3 px-4 text-negro dark:text-white">
                                            {{ substr($b->class_time, 0, 5) }}
                                        </td>

                                        <td class="py-3 px-4">
                                            <span
                                                class="inline-block px-3 py-1 rounded-full text-xs font-semibold shadow-sm {{ $badge }}">
                                                {{ $statusLabel }}
                                            </span>
                                        </td>

                                        <td class="py-3 px-4">
                                            <div class="flex justify-start gap-2">

                                                {{-- BOTÓN EDITAR --}}
                                                @if($b->status !== 'cancelled')
                                                    @if($isEditable)
                                                        <a href="{{ route('user.bookings.edit', $b) }}"
                                                            class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-white border border-azul text-azul hover:bg-azul hover:text-beige2 transition">
                                                            ✏️ Editar
                                                        </a>
                                                    @else
                                                        <span
                                                            class="relative group inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-400 border border-gray-300 cursor-not-allowed">
                                                            ✏️ Editar
                                                            <span
                                                               class="absolute -top-8 left-1/2 transform -translate-x-1/2 whitespace-nowrap bg-gray-800 text-white text-xs rounded px-2 py-1 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">
                                                                No se puede editar con menos de 24 horas de antelación.
                                                            </span>
                                                        </span>
                                                    @endif

                                                    {{-- BOTÓN CANCELAR --}}
                                                    @if($hoursUntil < 0)
                                                        <span 
                                                            class="relative group inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-white border border-gray-300 text-gray-400 cursor-not-allowed opacity-60">
                                                            ❌ Cancelar
                                                            <span
                                                                class="absolute -top-8 left-1/2 transform -translate-x-1/2 whitespace-nowrap bg-gray-800 text-white text-xs rounded px-2 py-1 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">
                                                                La clase ya ha pasado
                                                            </span>
                                                        </span>
                                                    @else
                                                        <form method="POST" action="{{ route('user.bookings.destroy', $b) }}"
                                                            class="inline-block">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button"
                                                                    onclick="openCancelModal('{{ route('user.bookings.destroy', $b) }}', '{{ $isRefundable ? '1' : '0' }}')"
                                                                    class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-white border border-rojo text-rojo hover:bg-rojo hover:text-beige2 transition">
                                                                ❌ Cancelar
                                                            </button>

                                                           
                                                        </form>
                                                    @endif

                                                    {{-- BOTÓN UNIRSE --}}
                                                    @if($b->status === 'confirmed' && !empty($b->meeting_url))
                                                        @if(isset($start) && $start->isFuture())
                                                            <a href="{{ route('bookings.join', $b) }}" target="_blank"
                                                                class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-white border border-ok text-ok hover:bg-ok hover:text-beige2 transition">
                                                                ▶️ Unirse
                                                            </a>
                                                        @else
                                                            <span 
                                                                class="relative group inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-white border border-gray-300 text-gray-400 cursor-not-allowed opacity-60">
                                                                ▶️ Unirse
                                                                <span
                                                                    class="absolute -top-8 left-1/2 transform -translate-x-1/2 whitespace-nowrap bg-gray-800 text-white text-xs rounded px-2 py-1 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">
                                                                    La clase ya ha pasado
                                                                </span>
                                                            </span>
                                                        @endif
                                                    @endif
                                                @endif

                                            </div>
                                        </td>
                                    </tr>

                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

      
                {{-- HISTORIAL DE CLASES --}}
              
                @if($hasHistory)
                    <div class="rounded-2xl shadow-xl overflow-hidden border border-beige mt-8">

                        <div class="bg-azul text-beige2 px-6 py-4">
                            <h2 class="font-semibold text-xl leading-tight">Historial de clases</h2>
                        </div>

                        {{-- Movil: tarjetas para historial (md:hidden) --}}
                        <div class="md:hidden space-y-3 p-4">
                            @foreach($history as $b)
                                @php
                                    $status = $b->status;
                                    $statusLabel = __('statuses.' . $status);
                                    $badge = match ($status) {
                                        'confirmed' => 'bg-ok text-white',
                                        'pending' => 'bg-info text-negro',
                                        'cancelled' => 'bg-rojo text-beige2',
                                        default => 'bg-gray-200 text-negro'
                                    };
                                @endphp

                                <div class="bg-white p-4 rounded-lg border shadow-sm dark:bg-slate-900 dark:text-white">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <div class="text-xs text-gray-500 dark:text-white">Fecha</div>
                                            <div class="font-medium text-sm text-azul dark:text-white">{{ \Carbon\Carbon::parse($b->class_date)->format('d/m/Y') }}</div>
                                            <div class="text-xs text-gray-500 mt-2 dark:text-white">Hora</div>
                                            <div class="text-sm text-azul dark:text-white">{{ substr($b->class_time, 0, 5) }}</div>
                                            <div class="text-xs text-gray-500 mt-2 dark:text-white">Estado</div>
                                            <div class="inline-block mt-1 px-2 py-1 rounded-full text-xs font-semibold {{ $badge }}">{{ $statusLabel }}</div>
                                        </div>

                                        <div class="text-xs text-gray-500 dark:text-white">
                                            Clase pasada
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Desktop: tabla historial --}}
                        <div class="hidden md:block overflow-x-auto">
                        <table class="w-full table-fixed text-sm">
                            <colgroup>
                                <col style="width:25%"> <!-- Fecha -->
                                <col style="width:25%"> <!-- Hora -->
                                <col style="width:25%"> <!-- Estado -->
                                <col style="width:25%"> <!-- Observaciones / vacío -->
                            </colgroup>
                            <thead class="bg-beige/80 text-azul uppercase text-xs tracking-wider">
                                <tr>
                                    <th class="py-3 px-4 text-left">Fecha</th>
                                    <th class="py-3 px-4 text-left">Hora</th>
                                    <th class="py-3 px-4 text-left">Estado</th>
                                    <th class="py-3 px-4 text-left">Acciones</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($history as $b)
                                    @php
                                        $status = $b->status;
                                        $statusLabel = __('statuses.' . $status);

                                        $badge = match ($status) {
                                            'confirmed' => 'bg-ok text-white',
                                            'pending' => 'bg-info text-negro',
                                            'cancelled' => 'bg-rojo text-beige2',
                                            default => 'bg-gray-200 text-negro'
                                        };
                                    @endphp

                                    <tr
                                        class="odd:bg-beige2 even:bg-white hover:bg-azul/5 transition dark:odd:bg-slate-800 dark:even:bg-slate-900 dark:hover:bg-slate-700">
                                        <td class="py-3 px-4 font-medium text-negro dark:text-white">
                                            {{ \Carbon\Carbon::parse($b->class_date)->format('d/m/Y') }}
                                        </td>
                                        <td class="py-3 px-4 text-negro dark:text-white">
                                            {{ substr($b->class_time, 0, 5) }}
                                        </td>
                                        <td class="py-3 px-4">
                                            <span
                                                class="inline-block px-3 py-1 rounded-full text-xs font-semibold shadow-sm {{ $badge }}">
                                                {{ $statusLabel }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 text-xs text-gray-500">
                                            Clase pasada
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

            @endif
        </div>
    </div>

   
{{-- MODAL DE CONFIRMACIÓN: usado por el botón Cancelar (llama a openCancelModal/closeCancelModal de user-bookings-modal.js) --}}
<div id="cancelModal"
     class="fixed inset-0 hidden items-center justify-center bg-black/40 z-50">

    {{-- Contenedor del modal: centrado, con fondo oscuro --}}
    <div class="bg-white rounded-2xl p-6 max-w-sm mx-auto shadow-xl text-center border border-beige">
        {{-- Título del modal --}}
        <h3 class="text-lg font-semibold text-azul mb-3">Cancelar clase</h3>

        {{-- Texto dinámico: se llena con JS según si es reembolsable --}}
        <p id="cancelModalText" class="text-gray-700 mb-6"></p>

        {{-- Botones de acción: cerrar modal o confirmar cancelación --}}
        <div class="flex justify-center gap-3">
            <button onclick="closeCancelModal()"
                    class="px-4 py-2 rounded-lg bg-gray-200 text-gray-800 hover:bg-gray-300 transition">
                No cancelar
            </button>

            {{-- Formulario oculto para envío POST de cancelación --}}
            <form id="cancelModalForm" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="px-4 py-2 rounded-lg bg-rojo text-beige2 hover:bg-red-700 transition">
                    Sí, cancelar
                </button>
            </form>
        </div>
    </div>

</div>




@endsection
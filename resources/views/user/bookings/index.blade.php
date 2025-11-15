@extends('layouts.site')

@section('title', 'Mis clases · Gran Bretania')

@section('header')
    @include('layouts.navigation')
@endsection

@section('content')


    <script>
        function confirmCancel(btn) {
            var refundable = btn.getAttribute('data-refundable') === '1';
            var msg = '¿Cancelar reserva?';
            if (!refundable) {
                msg += '\n\nAviso: esta cancelación no tendrá derecho a reembolso por realizarse con poca antelación.';
            }
            return confirm(msg);
        }
    </script>
    <div class="py-6">

        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if(session('ok'))
                <div class="bg-green-100 text-green-800 p-3 rounded">{{ session('ok') }}</div>
            @endif

            @if($bookings->isEmpty())
                <div class="bg-white p-6 shadow sm:rounded-lg">No tienes reservas.</div>
            @else
                <div class="rounded-2xl shadow-xl overflow-hidden border border-beige">

                    {{-- CABECERA AZUL CORPORATIVA --}}
                    <div class="bg-azul text-beige2 px-6 py-4">
                        <h2 class="font-semibold text-xl text-beige2-800 leading-tight">Mis clases</h2>
                    </div>

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
                            @foreach ($bookings as $b)
                                @php
                                    // BADGES corporativos
                                    $status = $b->status;
                                    $statusLabel = ucfirst($status);

                                    $badge = match ($status) {
                                        'confirmed' => 'bg-ok text-white',
                                        'pending' => 'bg-info text-negro',
                                        'cancelled' => 'bg-rojo text-beige2',
                                        default => 'bg-gray-200 text-negro'
                                    };

                                    // Cálculos de tus reglas
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
                                <tr class="odd:bg-beige2 even:bg-white hover:bg-azul/5 transition">

                                    <td class="py-3 px-4 font-medium text-negro">
                                        {{ \Carbon\Carbon::parse($b->class_date)->format('d/m/Y') }}
                                    </td>

                                    <td class="py-3 px-4 text-negro">
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
                                                    <button onclick="alert('{{ addslashes($reasonText) }}');"
                                                        class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-400 border border-gray-300 cursor-not-allowed">
                                                        ✏️ Editar
                                                    </button>
                                                @endif

                                                {{-- BOTÓN CANCELAR --}}
                                                <form method="POST" action="{{ route('user.bookings.destroy', $b) }}"
                                                    class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" onclick="return confirmCancel(this)"
                                                        data-refundable="{{ $isRefundable ? '1' : '0' }}"
                                                        class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-white border border-rojo text-rojo hover:bg-rojo hover:text-beige2 transition">
                                                        ❌ Cancelar
                                                    </button>
                                                </form>

                                                {{-- BOTÓN UNIRSE --}}
                                                @if($b->status === 'confirmed' && !empty($b->meeting_url))
                                                    <a href="{{ route('bookings.join', $b) }}" target="_blank"
                                                        class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-white border border-ok text-ok hover:bg-ok hover:text-beige2 transition">
                                                        ▶️ Unirse
                                                    </a>
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
        </div>
    </div>
    <script>
        function confirmCancel(btn) {
            var refundable = btn.getAttribute('data-refundable') === '1';
            var msg = '¿Cancelar reserva?';
            if (!refundable) {
                msg += '\n\nAviso: esta cancelación no tiene derecho a reembolso por realizarse con poca antelación.';
            }
            return confirm(msg);
        }
    </script>

    {{-- Las traducciones ahora tienen su propia página en /mis-traducciones --}}

@endsection
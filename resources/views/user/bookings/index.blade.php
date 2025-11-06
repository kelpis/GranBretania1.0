<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Mis reservas</h2>
    </x-slot>
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
                <div class="bg-white p-6 shadow sm:rounded-lg">
                    <table class="w-full table-auto">
                        <thead>
                            <tr class="text-left border-b">
                                <th class="py-2">Fecha</th>
                                <th class="py-2">Hora</th>
                                <th class="py-2">Estado</th>
                                <th class="py-2">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bookings as $b)
                                <tr class="border-b">
                                    <td class="py-2">{{ \Carbon\Carbon::parse($b->class_date)->format('d/m/Y') }}</td>
                                    <td class="py-2">{{ substr($b->class_time,0,5) }}</td>
                                    <td class="py-2">{{ ucfirst($b->status) }}</td>
                                    <td class="py-2 space-x-2">

                                        @if($b->status !== 'cancelled')
                                            @php
                                                $start = \Carbon\Carbon::parse($b->class_date . ' ' . substr($b->class_time,0,5));
                                                // diffInHours with absolute=false gives positive when $start is in the future
                                                $hoursUntil = now()->diffInHours($start, false);
                                                $hasEditsLeft = (($b->edit_count ?? 0) < 2);
                                                $isEditable = ($hoursUntil >= 24) && $hasEditsLeft;
                                                $reasons = [];
                                                if ($hoursUntil < 0) {
                                                    $reasons[] = 'La clase ya ha pasado';
                                                } elseif ($hoursUntil < 24) {
                                                    $reasons[] = 'No puedes editar la reserva con menos de 24 horas de antelación';
                                                }
                                                if (!$hasEditsLeft) {
                                                    $reasons[] = 'Has alcanzado el límite de ediciones (2)';
                                                }
                                                $reasonText = count($reasons) ? implode('. ', $reasons) . '.' : 'No editable';
                                            @endphp
                                            @if($isEditable)
                                                <a href="{{ route('user.bookings.edit', $b) }}" class="text-blue-600">Editar</a>
                                            @else
                                                <a href="#" class="text-gray-500" title="{{ $reasonText }}" onclick="alert('{{ addslashes($reasonText) }}'); return false;">Editar</a>
                                            @endif

                                            <form method="POST" action="{{ route('user.bookings.destroy', $b) }}" style="display:inline">
                                                @csrf
                                                @method('DELETE')
                                                @php
                                                    $startCancel = \Carbon\Carbon::parse($b->class_date . ' ' . substr($b->class_time,0,5));
                                                    $hoursUntilCancel = now()->diffInHours($startCancel, false);
                                                    $isRefundable = ($hoursUntilCancel >= 24 && $b->paid && !empty($b->payment_intent));
                                                @endphp
                                                <button type="submit" class="text-red-600" data-refundable="{{ $isRefundable ? '1' : '0' }}" onclick="return confirmCancel(this)">Cancelar</button>
                                            </form>

                                            @if($b->status === 'confirmed' && !empty($b->meeting_url))
                                                <a href="{{ route('bookings.join', $b) }}" class="ml-2 text-green-600 underline" target="_blank">Unirse</a>
                                            @endif
                                        @endif
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
</x-app-layout>

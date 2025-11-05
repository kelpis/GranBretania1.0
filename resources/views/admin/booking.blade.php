<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Reservas confirmadas (pagadas)</h2>
    </x-slot>

    <div class="py-6 space-y-6 max-w-6xl mx-auto">
        {{-- Mensajes de estado --}}
        @if (session('ok'))
            <div class="p-3 rounded bg-green-50 border border-green-200 text-green-800">
                {{ session('ok') }}
            </div>
        @endif

        @if (session('error'))
            <div class="p-3 rounded bg-red-50 border border-red-200 text-red-800">
                {{ session('error') }}
            </div>
        @endif

        {{-- Confirmadas / pagadas --}}
        <div class="bg-white p-4 rounded shadow">
            <h3 class="font-semibold mb-3">Reservas pagadas pendientes de confirmar</h3>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left border-b">
                            <th class="py-2 pr-3">Fecha</th>
                            <th class="py-2 pr-3">Hora</th>
                            <th class="py-2 pr-3">Nombre</th>
                            <th class="py-2 pr-3">Email</th>
                            <th class="py-2 pr-3">Notas</th>
                            <th class="py-2 pr-3">Estado</th>
                            <th class="py-2 pr-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($confirmadas as $b)
                            <tr class="border-b">
                                <td class="py-2 pr-3">{{ \Carbon\Carbon::parse($b->class_date)->format('d/m/Y') }}</td>
                                <td class="py-2 pr-3">{{ substr($b->class_time, 0, 5) }}</td>
                                <td class="py-2 pr-3">{{ $b->name }}</td>
                                <td class="py-2 pr-3">
                                    <a href="mailto:{{ $b->email }}" class="underline text-blue-600">
                                        {{ $b->email }}
                                    </a>
                                </td>
                                <td class="py-2 pr-3">{{ $b->notes }}</td>
                                <td class="py-2 pr-3">
                                    @if($b->paid)
                                        <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-800">Pagada</span>
                                    @endif
                                    @if(!empty($b->refunded))
                                        <span class="px-2 py-1 text-xs rounded bg-yellow-100 text-yellow-800">Devuelta</span>
                                    @endif
                                </td>

                                <td class="py-2 pr-3">
                                    <div class="flex flex-col gap-2">

                                        {{-- Confirmar (envía email con link de reunión) --}}
                                        <form method="POST" action="{{ route('admin.bookings.confirm', $b) }}">
                                            @csrf
                                            @method('PATCH')
                                            <div class="flex items-center gap-2">
                                                <input name="meeting_url" type="url"
                                                    placeholder="https://meet.google.com/xxx-xxxx-xxx"
                                                    value="{{ $b->meeting_url ?? '' }}"
                                                    class="px-2 py-1 border rounded text-sm w-64"
                                                    aria-label="URL videollamada" />
                                                <button type="submit"
                                                    class="px-3 py-1 rounded bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                    Confirmar
                                                </button>
                                            </div>
                                        </form>

                                        {{-- Cancelar (sin devolución) --}}
                                        <form method="POST" action="{{ route('admin.bookings.cancel', $b) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    onclick="return confirm('¿Seguro que deseas cancelar esta reserva sin devolución?')"
                                                    class="px-3 py-1 rounded bg-gray-500 text-white hover:bg-gray-600">
                                                Cancelar
                                            </button>
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
                                                        class="px-3 py-1 rounded bg-red-700 text-white hover:bg-red-800">
                                                    Cancelar y devolver
                                                </button>
                                            </form>
                                        @endif

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-3 text-gray-500 text-center">
                                    No hay reservas confirmadas o pagadas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Canceladas recientes --}}
        <div class="bg-white p-4 rounded shadow">
            <h3 class="font-semibold mb-3">Canceladas recientes</h3>
            <ul class="list-disc pl-6 text-sm">
                @forelse ($canceladas->take(5) as $b)
                    <li>
                        {{ \Carbon\Carbon::parse($b->class_date)->format('d/m/Y') }}
                        {{ substr($b->class_time, 0, 5) }} — {{ $b->name }} ({{ $b->email }})
                        @if(!empty($b->refunded))
                            <span class="ml-2 px-2 py-0.5 text-xs rounded bg-yellow-100 text-yellow-800">Devuelta</span>
                        @endif
                    </li>
                @empty
                    <li class="text-gray-500">Sin canceladas recientes.</li>
                @endforelse
            </ul>
        </div>
    </div>
</x-app-layout>

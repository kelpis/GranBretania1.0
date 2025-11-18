<x-app-layout>
    
    <div class="bg-beige2 dark:bg-slate-950 -mx-4 sm:-mx-6 lg:-mx-8">
          <div class="pt-12 pb-8 max-w-6xl mx-auto space-y-16 px-4 sm:px-6 lg:px-8">

        {{-- Mensajes de estado --}}
        @if (session('ok'))
            <div class="p-3 rounded-xl bg-ok/10 border border-ok/40 text-ok text-sm dark:bg-emerald-950 dark:text-emerald-100 dark:border-emerald-500/60">
                {{ session('ok') }}
            </div>
        @endif

        @if (session('error'))
            <div class="p-3 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm dark:bg-rose-900 dark:text-rose-100 dark:border-rose-500/70">
                {{ session('error') }}
            </div>
        @endif

        {{-- PENDIENTES DE CONFIRMAR --}}
        <div class="rounded-2xl shadow-xl overflow-hidden border border-beige bg-beige2 dark:bg-slate-950 dark:border-slate-700">
            <div class="bg-azul text-beige2 px-6 py-4 flex items-center justify-between">
                <h3 class="font-semibold text-lg">Reservas pendientes de confirmar</h3>
                
            </div>

            <div>
                <!-- Mobile: tarjetas apiladas -->
                <div class="md:hidden space-y-3">
                    @forelse ($pendientes as $b)
                        <div class="bg-white p-4 rounded-lg border shadow-sm dark:bg-slate-900 dark:text-slate-100">
                            <div class="flex justify-between items-start">
                                <div>
                                    <div class="text-xs text-gray-500">Fecha</div>
                                    <div class="font-medium text-sm text-azul">{{ \Carbon\Carbon::parse($b->class_date)->format('d/m/Y') }}</div>
                                    <div class="text-xs text-gray-500 mt-2">Hora</div>
                                    <div class="text-sm text-azul">{{ substr($b->class_time, 0, 5) }}</div>
                                </div>

                                <div class="text-right">
                                    <div class="text-xs text-gray-500">Estado</div>
                                    <div class="mt-1">
                                        @if($b->paid)
                                            <span class="inline-block px-2.5 py-1 text-xs rounded-full bg-ok text-white font-semibold">Pagada</span>
                                        @endif
                                        @if(!empty($b->refunded))
                                            <span class="inline-block px-2.5 py-1 text-xs rounded-full bg-info text-negro font-semibold">Devuelta</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3">
                                <div class="text-xs text-gray-500">Nombre</div>
                                <div class="font-medium">{{ $b->name }}</div>

                                <div class="text-xs text-gray-500 mt-2">Email</div>
                                <div class="truncate">
                                    <a href="mailto:{{ $b->email }}" class="underline text-azul dark:text-white hover:text-rojo dark:hover:text-rose-100">{{ $b->email }}</a>
                                </div>

                                @if(!empty($b->notes))
                                    <div class="text-xs text-gray-500 mt-2">Notas</div>
                                    <div class="text-sm">{{ $b->notes }}</div>
                                @endif
                            </div>

                            <div class="mt-3 flex flex-col gap-2">
                                <form method="POST" action="{{ route('admin.bookings.confirm', $b) }}">
                                    @csrf
                                    @method('PATCH')
                                    <div class="flex flex-col sm:flex-row gap-2">
                                        <input name="meeting_url" type="url" placeholder="https://meet.google.com/xxx-xxxx-xxx" value="{{ $b->meeting_url ?? '' }}" class="px-2 py-1 border border-beige rounded-lg text-sm w-full focus:ring-azul focus:border-azul" aria-label="URL videollamada" />
                                        <button type="submit" class="px-3 py-1 rounded-full bg-azul text-beige2 text-xs font-medium hover:bg-rojo transition">Confirmar</button>
                                    </div>
                                </form>

                                @php
                                    $fechaHoraClase = \Carbon\Carbon::parse($b->class_date.' '.substr($b->class_time,0,5));
                                @endphp
                                @if($b->paid && !$b->refunded && $fechaHoraClase->isFuture())
                                    <form method="POST" action="{{ route('admin.bookings.refund', $b) }}">
                                        @csrf
                                        <button type="submit" onclick="return confirm('¿Devolver el pago y cancelar esta reserva?');" class="px-3 py-1 rounded-full bg-rojo text-beige2 text-xs font-medium hover:bg-red-800 transition">Cancelar y devolver</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="py-4 text-center text-gray-500 dark:text-slate-300">No hay reservas nuevas.</div>
                    @endforelse
                </div>

                <!-- Desktop/tablet: tabla -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full text-sm dark:text-beige2">
                        <thead class="bg-beige/80 text-azul uppercase text-xs tracking-wider dark:bg-slate-800/80 dark:text-beige2 dark:border-slate-700">
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
                                <tr class="odd:bg-beige2 even:bg-white hover:bg-azul/5 transition dark:odd:bg-slate-800 dark:even:bg-slate-900 dark:hover:bg-slate-700">
                                    <td class="py-3 px-4">{{ \Carbon\Carbon::parse($b->class_date)->format('d/m/Y') }}</td>
                                    <td class="py-3 px-4">{{ substr($b->class_time, 0, 5) }}</td>
                                    <td class="py-3 px-4">{{ $b->name }}</td>
                                    <td class="py-3 px-4"><a href="mailto:{{ $b->email }}" class="underline text-azul dark:text-white hover:text-rojo dark:hover:text-rose-100">{{ $b->email }}</a></td>
                                    <td class="py-3 px-4">{{ $b->notes }}</td>
                                    <td class="py-3 px-4">
                                        @if($b->paid)
                                            <span class="inline-block px-2.5 py-1 text-xs rounded-full bg-ok text-white font-semibold">Pagada</span>
                                        @endif
                                        @if(!empty($b->refunded))
                                            <span class="inline-block px-2.5 py-1 text-xs rounded-full bg-info text-negro font-semibold">Devuelta</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="flex flex-col gap-2 items-end">
                                            <form method="POST" action="{{ route('admin.bookings.confirm', $b) }}">
                                                @csrf
                                                @method('PATCH')
                                                <div class="flex flex-wrap items-center gap-2">
                                                    <input name="meeting_url" type="url" placeholder="https://meet.google.com/xxx-xxxx-xxx" value="{{ $b->meeting_url ?? '' }}" class="px-2 py-1 border border-beige rounded-lg text-sm w-64 focus:ring-azul focus:border-azul" aria-label="URL videollamada" />
                                                    <button type="submit" class="px-3 py-1 rounded-full bg-azul text-beige2 text-xs font-medium hover:bg-rojo transition focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-azul">Confirmar</button>
                                                </div>
                                            </form>

                                            @php
                                                $fechaHoraClase = \Carbon\Carbon::parse($b->class_date.' '.substr($b->class_time,0,5));
                                            @endphp
                                            @if($b->paid && !$b->refunded && $fechaHoraClase->isFuture())
                                                <form method="POST" action="{{ route('admin.bookings.refund', $b) }}">
                                                    @csrf
                                                    <button type="submit" onclick="return confirm('¿Devolver el pago y cancelar esta reserva?');" class="px-3 py-1 rounded-full bg-rojo text-beige2 text-xs font-medium hover:bg-red-800 transition">Cancelar y devolver</button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-4 px-4 text-gray-500 text-center dark:text-slate-300">No hay reservas nuevas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- YA CONFIRMADAS --}}
        <div class="rounded-2xl shadow-xl overflow-hidden border border-beige bg-beige2 dark:bg-slate-950 dark:border-slate-700">
            <div class="bg-azul text-beige2 px-6 py-4">
                <h3 class="font-semibold text-lg">Reservas confirmadas</h3>
            </div>

            @php
                // Filtra las confirmadas por estado si $confirmadas es una colección
                $ya_confirmadas = isset($confirmadas) && method_exists($confirmadas, 'where')
                    ? $confirmadas->where('status', 'confirmed')
                    : (array_filter($confirmadas ?? [], fn($x) => (isset($x->status) ? $x->status === 'confirmed' : false)) );
            @endphp

            <!-- Mobile: tarjetas apiladas -->
            <div class="md:hidden space-y-3 p-4">
                @forelse ($ya_confirmadas as $b)
                    <div class="bg-white p-4 rounded-lg border shadow-sm dark:bg-slate-900 dark:text-slate-100">
                        <div class="flex justify-between items-start">
                            <div>
                                <div class="text-xs text-gray-500">Fecha</div>
                                <div class="font-medium text-sm text-azul">{{ \Carbon\Carbon::parse($b->class_date)->format('d/m/Y') }}</div>
                                <div class="text-xs text-gray-500 mt-2">Hora</div>
                                <div class="text-sm text-azul">{{ substr($b->class_time, 0, 5) }}</div>
                                <div class="text-xs text-gray-500 mt-2">Nombre</div>
                                <div class="font-medium">{{ $b->name }}</div>
                                <div class="text-xs text-gray-500 mt-2">Email</div>
                                <div class="truncate"><a href="mailto:{{ $b->email }}" class="underline text-azul dark:text-white">{{ $b->email }}</a></div>
                            </div>

                            <div class="text-right">
                                <div class="text-xs text-gray-500">Estado</div>
                                <div class="mt-1">
                                    @if($b->paid && !$b->refunded)
                                        <span class="inline-block px-2.5 py-1 text-xs rounded-full bg-ok text-white font-semibold">Pagada</span>
                                    @else
                                        <span class="inline-block px-2.5 py-1 text-xs rounded-full bg-gray-400 text-white font-semibold">--</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="mt-3 flex flex-wrap gap-2">
                            @php $fechaHoraClase = \Carbon\Carbon::parse($b->class_date.' '.substr($b->class_time,0,5)); @endphp
                            @if($b->paid && !$b->refunded && $fechaHoraClase->isFuture())
                                <form method="POST" action="{{ route('admin.bookings.refund', $b) }}">
                                    @csrf
                                    <button type="submit" onclick="return confirm('¿Devolver el pago y cancelar esta reserva?');" class="px-3 py-1 rounded-full bg-rojo text-beige2 text-xs font-medium hover:bg-red-800 transition">Cancelar y devolver</button>
                                </form>
                            @else
                                <span class="text-gray-500 text-xs">No aplicable</span>
                            @endif

                            @if(!empty($b->meeting_url))
                                <a href="{{ route('bookings.join', $b) }}" target="_blank" class="px-3 py-1 rounded-full bg-azul text-beige2 text-xs font-medium">Unirse</a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="py-4 text-center text-gray-500 dark:text-slate-300">No hay reservas ya confirmadas.</div>
                @endforelse
            </div>

            <!-- Desktop/tablet: tabla -->
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full text-sm dark:text-beige2">
                    <thead class="bg-beige/80 text-azul uppercase text-xs tracking-wider dark:bg-slate-800/80 dark:text-beige2 dark:border-slate-700">
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
                        @forelse ($ya_confirmadas as $b)
                            <tr class="odd:bg-beige2 even:bg-white hover:bg-azul/5 transition dark:odd:bg-slate-800 dark:even:bg-slate-900 dark:hover:bg-slate-700">
                                <td class="py-3 px-4">{{ \Carbon\Carbon::parse($b->class_date)->format('d/m/Y') }}</td>
                                <td class="py-3 px-4">{{ substr($b->class_time, 0, 5) }}</td>
                                <td class="py-3 px-4">{{ $b->name }}</td>
                                <td class="py-3 px-4"><a href="mailto:{{ $b->email }}" class="underline text-azul dark:text-white hover:text-rojo dark:hover:text-rose-100">{{ $b->email }}</a></td>
                                <td class="py-3 px-4">
                                    @if(!empty($b->meeting_url))
                                        <a href="{{ route('bookings.join', $b) }}" target="_blank" class="underline text-azul dark:text-white hover:text-rojo dark:hover:text-rose-100 break-all text-xs">{{ $b->meeting_url }}</a>
                                    @else
                                        <span class="text-gray-500 text-sm">Sin enlace</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    @php $fechaHoraClase = \Carbon\Carbon::parse($b->class_date.' '.substr($b->class_time,0,5)); @endphp
                                    @if($b->paid && !$b->refunded && $fechaHoraClase->isFuture())
                                        <form method="POST" action="{{ route('admin.bookings.refund', $b) }}">
                                            @csrf
                                            <button type="submit" onclick="return confirm('¿Devolver el pago y cancelar esta reserva?');" class="px-3 py-1 rounded-full bg-rojo text-beige2 text-xs font-medium hover:bg-red-800 transition">Cancelar y devolver</button>
                                        </form>
                                    @else
                                        <span class="text-gray-500 text-xs">No aplicable</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-4 px-4 text-gray-500 text-center dark:text-slate-300">No hay reservas ya confirmadas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- CANCELADAS RECIENTES --}}
        <div class="rounded-2xl shadow-xl overflow-hidden border border-beige bg-beige2 dark:bg-slate-950 dark:border-slate-700">
            <div class="bg-azul text-beige2 px-6 py-4">
                <h3 class="font-semibold text-lg">Canceladas recientes</h3>
            </div>

            <div class="bg-white px-6 py-4 dark:bg-slate-900 dark:text-slate-100">
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
                        <li class="text-gray-500 dark:text-slate-300">
                            Sin canceladas recientes.
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>
        </div>
    </div>
</x-app-layout>

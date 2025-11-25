<x-app-layout>
    
    <div class="bg-beige2 dark:bg-slate-950 -mx-4 sm:-mx-6 lg:-mx-8">
        <div class="py-8 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        {{-- BIENVENIDA --}}
        <section class="rounded-2xl bg-azul text-beige2 shadow-xl px-6 py-5 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold mb-1">
                    Hola, {{ auth()->user()->name }} 
                </h1>
                <p class="text-sm text-beige/90">
                    Desde aquí puedes revisar tus clases, traducciones y ajustar tu disponibilidad.
                </p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.bookings.index') }}"
                   class="inline-flex items-center px-4 py-2 rounded-full bg-beige text-azul text-sm font-medium hover:bg-white transition">
                    Ver reservas
                </a>
                <a href="{{ route('admin.translations.index') }}"
                   class="inline-flex items-center px-4 py-2 rounded-full border border-beige2 text-beige2 text-sm hover:bg-beige2 hover:text-azul transition">
                    Ver traducciones
                </a>
            </div>
        </section>

        {{-- TARJETAS RESUMEN --}}
        @php
            // Si aún no pasas stats desde el controlador, puedes dejar valores por defecto
            $totalBookings       = $stats['total_bookings']        ?? 0;
            $upcomingBookings    = $stats['upcoming_bookings']     ?? 0;
            $todayClasses        = $stats['today_classes']         ?? 0;
            $pendingTranslations = $stats['pending_translations']  ?? 0;
        @endphp

        <section class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <article class="rounded-2xl bg-white border border-beige shadow p-4 dark:bg-slate-900 dark:border-slate-700 dark:text-slate-100">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1 dark:text-slate-100">
                    Reservas totales
                </h3>
                <p class="text-3xl font-bold text-azul dark:text-slate-100">{{ $totalBookings }}</p>
                <p class="text-xs text-gray-500 mt-1 dark:text-slate-100">
                    Historial completo de clases reservadas.
                </p>
            </article>

            <article class="rounded-2xl bg-beige border border-beige shadow p-4 dark:bg-slate-800 dark:border-slate-700 dark:text-beige2">
                <h3 class="text-xs font-semibold text-gray-700 uppercase tracking-wide mb-1 dark:text-beige2">
                    Próximas clases
                </h3>
                <p class="text-3xl font-bold text-azul dark:text-slate-100">{{ $upcomingBookings }}</p>
                <p class="text-xs text-gray-700 mt-1 dark:text-slate-100">
                    Clases programadas en el futuro.
                </p>
            </article>

            <article class="rounded-2xl bg-azul text-beige2 shadow p-4">
                <h3 class="text-xs font-semibold text-beige uppercase tracking-wide mb-1">
                    Clases para hoy
                </h3>
                <p class="text-3xl font-bold">{{ $todayClasses }}</p>
                <p class="text-xs text-beige/90 mt-1">
                    Número de sesiones previstas para hoy.
                </p>
            </article>

            <article class="rounded-2xl bg-rojo text-beige2 shadow p-4">
                <h3 class="text-xs font-semibold uppercase tracking-wide mb-1">
                    Traducciones pendientes
                </h3>
                <p class="text-3xl font-bold">{{ $pendingTranslations }}</p>
                <p class="text-xs text-beige/90 mt-1">
                    Solicitudes aún sin completar.
                </p>
            </article>
        </section>

        {{-- DOS COLUMNAS: CLASES + TRADUCCIONES --}}
        <section class="grid lg:grid-cols-2 gap-6">

            {{-- PRÓXIMAS CLASES --}}
            <div class="rounded-2xl border border-beige bg-beige2 shadow dark:bg-slate-950 dark:border-slate-700">
                <div class="bg-azul text-beige2 px-5 py-3 rounded-t-2xl flex items-center justify-between">
                    <h3 class="font-semibold text-sm md:text-base">Próximas clases</h3>
                    <a href="{{ route('admin.bookings.index') }}"
                       class="text-xs underline hover:text-beige">
                        Ver todas
                    </a>
                </div>

                <div class="p-5">
                    @if(isset($nextBookings) && $nextBookings->count())
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-xs md:text-sm text-azul dark:text-beige2">
                                <thead class="bg-beige/80 border-b border-beige dark:bg-slate-800/80 dark:border-slate-700">
                                    <tr>
                                        <th class="py-2 px-2 text-left">Fecha</th>
                                        <th class="py-2 px-2 text-left">Hora</th>
                                        <th class="py-2 px-2 text-left">Alumno</th>
                                        <th class="py-2 px-2 text-left">Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                        @foreach($nextBookings as $b)
                                        <tr class="odd:bg-beige2 even:bg-white hover:bg-azul/5 transition dark:odd:bg-slate-800 dark:even:bg-slate-900 dark:hover:bg-slate-700">
                                            <td class="py-2 px-2">
                                                {{ \Carbon\Carbon::parse($b->class_date)->format('d/m/Y') }}
                                            </td>
                                            <td class="py-2 px-2">
                                                {{ substr($b->class_time, 0, 5) }}
                                            </td>
                                            <td class="py-2 px-2">
                                                {{ $b->user->name ?? $b->name }}
                                            </td>
                                            <td class="py-2 px-2">
                                                @php
                                                    $status = $b->status ?? 'pending';
                                                    $badge = match($status) {
                                                        'confirmed' => 'bg-ok text-white',
                                                        'cancelled' => 'bg-rojo text-beige2',
                                                        default     => 'bg-info text-negro',
                                                    };
                                                @endphp
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold {{ $badge }}">
                                                    {{ __('statuses.' . $status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <p class="text-sm text-gray-600 dark:text-slate-300">
                            No hay clases próximas registradas.
                        </p>
                    @endif
                </div>
            </div>

            {{-- ÚLTIMAS TRADUCCIONES --}}
            <div class="rounded-2xl border border-beige bg-beige2 shadow dark:bg-slate-950 dark:border-slate-700">
                <div class="bg-azul text-beige2 px-5 py-3 rounded-t-2xl flex items-center justify-between">
                    <h3 class="font-semibold text-sm md:text-base">Últimas solicitudes de traducción</h3>
                    <a href="{{ route('admin.translations.index') }}"
                       class="text-xs underline hover:text-beige">
                        Ver todas
                    </a>
                </div>

                <div class="p-5">
                    @if(isset($recentTranslations) && $recentTranslations->count())
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-xs md:text-sm text-azul dark:text-beige2">
                                <thead class="bg-beige/80 border-b border-beige dark:bg-slate-800/80 dark:border-slate-700">
                                    <tr>
                                        <th class="py-2 px-2 text-left">Fecha</th>
                                        <th class="py-2 px-2 text-left">Nombre</th>
                                        <th class="py-2 px-2 text-left">Idiomas</th>
                                        <th class="py-2 px-2 text-left">Urgencia</th>
                                    </tr>
                                </thead>
                                <tbody>
                                        @foreach($recentTranslations as $tr)
                                        <tr class="odd:bg-beige2 even:bg-white hover:bg-azul/5 transition dark:odd:bg-slate-800 dark:even:bg-slate-900 dark:hover:bg-slate-700">
                                            <td class="py-2 px-2">
                                                {{ $tr->created_at->format('d/m/Y H:i') }}
                                            </td>
                                            <td class="py-2 px-2">
                                                {{ $tr->name }}
                                            </td>
                                            <td class="py-2 px-2">
                                                <span class="px-2 py-0.5 rounded-full bg-azul text-beige2 text-[11px] font-semibold">
                                                    {{ strtoupper($tr->source_lang) }} → {{ strtoupper($tr->target_lang) }}
                                                </span>
                                            </td>
                                            <td class="py-2 px-2">
                                                @php
                                                    $urgencyBadge = $tr->urgency === 'alta'
                                                        ? 'bg-rojo text-beige2'
                                                        : 'bg-ok text-white';
                                                @endphp
                                                <span class="px-2 py-0.5 rounded-full text-[11px] font-semibold {{ $urgencyBadge }}">
                                                    {{ ucfirst($tr->urgency) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-sm text-gray-600 dark:text-slate-300">
                            No hay solicitudes de traducción recientes.
                        </p>
                    @endif
                </div>
            </div>
        </section>

        

        </div>
    </div>
</x-app-layout>

<x-app-layout>

    {{--Vista Admin: Traducciones --}}

    <div class="bg-beige2 dark:bg-slate-950 -mx-4 sm:-mx-6 lg:-mx-8">
        <div class="py-8 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">


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



            {{-- CALCULADORA TRADUCCIÓN --}}
            {{-- Herramienta cliente para estimar precio; no envía datos al servidor --}}
            <div id="translation-calculator"
                class="mb-12 rounded-2xl border border-azul/20 bg-azul text-white shadow p-6">
                <h3 class="text-white font-semibold text-lg mb-4">Calculadora rápida de traducción</h3>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 items-end">

                    {{-- Campo palabras --}}
                    <div>
                        <label class="block text-sm font-medium text-white">Número de palabras</label>
                        <input id="calc-words" type="number" min="0" step="1" value="0"
                            class="mt-1 block w-full rounded-lg border border-beige bg-white text-azul p-2 dark:bg-slate-800 dark:text-slate-100 dark:border-slate-700" />
                    </div>

                    {{-- Campo precio por palabra --}}
                    <div>
                        <label class="block text-sm font-medium text-white">Precio por palabra (€)</label>
                        <input id="calc-price" type="number" min="0" step="0.01" value="0.10"
                            class="mt-1 block w-full rounded-lg border border-beige bg-white text-azul p-2 dark:bg-slate-800 dark:text-slate-100 dark:border-slate-700" />
                    </div>

                    {{-- Resultado calculado --}}
                    <div>
                        <label class="block text-sm font-medium text-white">Resultado</label>
                        <div id="calc-result"
                            class="mt-1 text-2xl font-bold text-azul bg-white rounded-lg h-10 flex items-center px-3 shadow-inner dark:bg-slate-900 dark:text-beige2">
                            €0.00
                        </div>
                    </div>
                </div>

                {{-- Botón reset --}}
                <div class="mt-4">
                    <button id="calc-reset" type="button"
                        class="px-4 py-1.5 rounded-full bg-rojo text-white text-sm font-medium hover:bg-red-700 transition">
                        Reset
                    </button>
                </div>
            </div>



            {{-- TABLA DE TRADUCCIONES --}}

            {{-- Listado responsive: tarjetas en móvil, tabla en desktop --}}
            <div
                class="rounded-2xl shadow-xl overflow-hidden border border-beige bg-beige2 dark:bg-slate-950 dark:border-slate-700">
                <div class="bg-azul text-beige2 px-6 py-4">
                    <h3 class="font-semibold text-lg">Traducciones</h3>
                </div>

                <div class="rounded-lg border border-azul/20 overflow-hidden">


                    {{-- RESPONSIVE MOVIL / TABLET VERSION (CARDS) --}}
                    {{-- Tarjetas con info básica y acciones inline --}}
                    <div class="lg:hidden space-y-3 p-4">
                        @forelse($items as $tr)
                            <div class="bg-white rounded-lg p-4 border shadow-sm dark:bg-slate-900 dark:text-slate-100">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <div class="text-xs text-gray-500">Fecha</div>
                                        <div class="font-medium text-sm text-azul dark:text-white">
                                            {{ $tr->created_at->format('d/m/Y H:i') }}
                                        </div>

                                        <div class="text-xs text-gray-500 mt-2">Nombre</div>
                                        <div class="font-medium">{{ $tr->user->name ?? '—' }}</div>
                                    </div>

                                    <div class="text-right">
                                        <div class="text-xs text-gray-500">Urgencia</div>
                                        {{-- Badge urgencia --}}
                                        @php
                                            $urgencyStyles = $tr->urgency === 'alta' ? 'bg-rojo text-beige2' : 'bg-ok text-white';
                                        @endphp
                                        <div class="mt-1">
                                            <span
                                                class="px-2 py-1 rounded-full text-xs font-medium {{ $urgencyStyles }}">{{ ucfirst($tr->urgency) }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <div class="text-xs text-gray-500">Email</div>
                                    <div class="truncate">
                                        @if($tr->user && $tr->user->email)
                                            <a href="mailto:{{ $tr->user->email }}"
                                                class="underline text-azul dark:text-white">{{ $tr->user->email }}</a>
                                        @else
                                            <span class="text-gray-600">—</span>
                                        @endif
                                    </div>

                                    <div class="text-xs text-gray-500 mt-2">Idiomas</div>
                                    <div class="mt-1">
                                        <span
                                            class="px-2 py-1 rounded-full text-xs bg-azul text-beige2 font-semibold">{{ strtoupper($tr->source_lang) }}</span>
                                        <span class="mx-1 font-bold">→</span>
                                        <span
                                            class="px-2 py-1 rounded-full text-xs bg-rojo text-beige2 font-semibold">{{ strtoupper($tr->target_lang) }}</span>
                                    </div>
                                </div>

                                <div class="mt-3 flex flex-col gap-2">
                                    {{-- Botón descargar archivo --}}
                                    <a href="{{ route('admin.translations.download', $tr->id) }}"
                                        class="inline-block px-3 py-1 rounded-full bg-azul text-beige2 text-xs font-medium">
                                        Descargar
                                    </a>

                                    {{-- Mostrar precio si asignado --}}
                                    @if ($tr->final_price_cents)
                                        <div class="text-sm text-gray-700 dark:text-slate-300">
                                            Estado: <span class="font-semibold">{{ ucfirst($tr->status) }}</span><br>
                                            Precio: <strong>{{ number_format($tr->final_price_cents / 100, 2, ',', '.') }}
                                                €</strong>
                                        </div>
                                    @endif

                                    {{-- Formulario asignar precio --}}
                                    @if (!$tr->final_price_cents)
                                        <form method="POST" action="{{ route('admin.translations.quote', $tr) }}"
                                            class="space-y-1">
                                            @csrf
                                            <label class="text-xs text-azul dark:text-white">Precio final (€)</label>
                                            <input type="number" name="amount_eur" step="0.01" min="1"
                                                class="w-24 border rounded px-2 py-1 text-sm dark:text-black"
                                                placeholder="0.00">

                                            <button class="px-3 py-1 rounded-full bg-azul text-beige2 text-xs font-medium">
                                                Guardar y generar pago
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Subir traducción final (MOVIL) --}}
                                    @if ($tr->status === 'paid')
                                        <form method="POST" action="{{ route('admin.translations.deliver', $tr) }}"
                                            enctype="multipart/form-data" class="space-y-1 mt-2">
                                            @csrf

                                            <label class="block text-xs text-azul dark:text-white">Subir traducción
                                                final</label>
                                            <input type="file" name="output_file"
                                                class="w-full text-xs border rounded p-1 bg-white dark:bg-slate-900 dark:text-beige2">

                                            <button type="submit"
                                                class="mt-1 px-3 py-1 rounded-full bg-ok text-white text-xs font-medium hover:bg-green-700 transition">
                                                Marcar como entregada
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>

                        @empty
                            <div class="py-4 text-center text-gray-500 dark:text-slate-300">
                                No hay traducciones.
                            </div>
                        @endforelse
                    </div>




                    {{-- DESKTOP (LG+) --}}
                    {{-- Tabla completa con todas las columnas y acciones --}}
                    <div class="hidden lg:block overflow-x-auto">
                        <table class="w-full text-sm text-azul dark:text-beige2">
                            <thead
                                class="bg-beige/80 uppercase text-xs tracking-wide border-b border-beige dark:bg-slate-800/80 dark:text-beige2 dark:border-slate-700">
                                <tr>
                                    <th class="p-3 text-left">Fecha</th>
                                    <th class="p-3 text-left">Nombre</th>
                                    <th class="p-3 text-left">Email</th>
                                    <th class="p-3 text-left">Idiomas</th>
                                    <th class="p-3 text-left">Urgencia</th>
                                    <th class="p-3 text-left">Archivo</th>
                                    <th class="p-3 text-left">Precio</th>
                                    <th class="p-3 text-left">Acciones</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($items as $tr)
                                    <tr
                                        class="odd:bg-beige2 even:bg-white hover:bg-azul/5 transition dark:odd:bg-slate-800 dark:even:bg-slate-900 dark:hover:bg-slate-700">
                                        <td class="p-3">{{ $tr->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="p-3">{{ $tr->user->name ?? '—' }}</td>
                                        <td class="p-3">
                                            @if($tr->user && $tr->user->email)
                                                <a href="mailto:{{ $tr->user->email }}"
                                                    class="text-azul underline hover:text-rojo transition dark:text-white dark:hover:text-rose-100">{{ $tr->user->email }}</a>
                                            @else
                                                <span class="text-gray-600">—</span>
                                            @endif
                                        </td>

                                        {{-- Idiomas --}}
                                        <td class="p-3">
                                            <div class="flex items-center gap-2 whitespace-nowrap overflow-hidden">
                                                <span
                                                    class="inline-block px-2 py-1 rounded-full text-xs bg-azul text-beige2 font-semibold">{{ strtoupper($tr->source_lang) }}</span>
                                                <span class="mx-1 font-bold text-sm">→</span>
                                                <span
                                                    class="inline-block px-2 py-1 rounded-full text-xs bg-rojo text-beige2 font-semibold">{{ strtoupper($tr->target_lang) }}</span>
                                            </div>
                                        </td>

                                        {{-- Urgencia --}}
                                        <td class="p-3">
                                            @php
                                                $urgencyStyles = $tr->urgency === 'alta'
                                                    ? 'bg-rojo text-beige2'
                                                    : 'bg-ok text-white';
                                            @endphp
                                            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $urgencyStyles }}">
                                                {{ ucfirst($tr->urgency) }}
                                            </span>
                                        </td>

                                        {{-- Archivo --}}
                                        <td class="p-3">
                                            <a href="{{ route('admin.translations.download', $tr->id) }}"
                                                class="px-3 py-1 rounded-full bg-azul text-beige2 text-xs font-medium hover:bg-rojo transition">
                                                Descargar
                                            </a>
                                        </td>

                                        {{-- Precio --}}
                                        <td class="p-3">
                                            @if ($tr->final_price_cents)
                                                {{ number_format($tr->final_price_cents / 100, 2, ',', '.') }} €
                                            @else
                                                <span class="text-xs text-gray-500 italic">Sin asignar</span>
                                            @endif
                                        </td>

                                        {{-- Acciones --}}
                                        <td class="p-3">
                                            <div class="space-y-2">

                                                {{-- Formulario para asignar precio --}}
                                                @if (!$tr->final_price_cents || $tr->status === 'pending')
                                                    <form method="POST" action="{{ route('admin.translations.quote', $tr) }}"
                                                        class="space-y-1">
                                                        @csrf
                                                        <label for="amount-{{ $tr->id }}"
                                                            class="block text-xs text-azul dark:text-white">
                                                            Precio final (€)
                                                        </label>
                                                        <div class="flex items-center gap-2">
                                                            <input id="amount-{{ $tr->id }}" name="amount_eur" type="number"
                                                                step="0.01" min="1"
                                                                class="w-24 border rounded px-2 py-1 text-sm dark:text-black"
                                                                placeholder="0.00">

                                                            <button type="submit"
                                                                class="px-3 py-1 rounded-full bg-azul text-beige2 text-xs font-medium hover:bg-rojo transition">
                                                                Guardar y generar
                                                            </button>
                                                        </div>
                                                    </form>

                                                @else
                                                    <div class="text-xs text-gray-700 dark:text-slate-300">
                                                        <div>
                                                            Estado:
                                                            <span class="font-semibold">
                                                                {{ ucfirst($tr->status) }}
                                                            </span>
                                                        </div>

                                                        <div>
                                                            Precio:
                                                            {{ number_format($tr->final_price_cents / 100, 2, ',', '.') }} €
                                                        </div>
                                                    </div>
                                                @endif
                                                {{-- Subir traducción final --}}
                                                @if ($tr->status === 'paid')
                                                    <form method="POST" action="{{ route('admin.translations.deliver', $tr) }}"
                                                        enctype="multipart/form-data" class="space-y-1">
                                                        @csrf

                                                        <label class="block text-xs text-azul dark:text-white">Subir traducción
                                                            final</label>

                                                        <input type="file" name="output_file"
                                                            class="w-full text-xs border rounded p-1 bg-white dark:bg-slate-900 dark:text-beige2">

                                                        <button type="submit"
                                                            class="mt-1 px-3 py-1 rounded-full bg-ok text-white text-xs font-medium hover:bg-green-700 transition">
                                                            Marcar como entregada
                                                        </button>
                                                    </form>
                                                @endif

                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6 px-4 md:px-0">
                        {{ $items->links() }}
                    </div>

                </div>
            </div>

        </div>
    </div>

</x-app-layout>
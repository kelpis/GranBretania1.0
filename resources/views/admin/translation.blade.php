<x-app-layout>


    <div class="py-8 max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">


        {{-- <div class="rounded-2xl shadow-xl overflow-hidden border border-beige bg-beige2 p-6"></div> --}}
        {{-- ========================= --}}
        {{-- CALCULADORA TRADUCCIÓN --}}
        {{-- ========================= --}}
        <div id="translation-calculator" class="mb-12 rounded-2xl border border-azul/20 bg-azul text-white shadow p-6">
            <h3 class="text-white font-semibold text-lg mb-4">Calculadora rápida de traducción</h3>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 items-end">

                {{-- Palabras --}}
                <div>
                    <label class="block text-sm font-medium text-white">Número de palabras</label>
                    <input id="calc-words" type="number" min="0" step="1" value="0"
                        class="mt-1 block w-full rounded-lg border border-beige bg-white text-azul p-2 focus:ring-azul focus:border-azul" />
                </div>

                {{-- Precio por palabra --}}
                <div>
                    <label class="block text-sm font-medium text-white">Precio por palabra (€)</label>
                    <input id="calc-price" type="number" min="0" step="0.01" value="0.10"
                        class="mt-1 block w-full rounded-lg border border-beige bg-white text-azul p-2 focus:ring-azul focus:border-azul" />
                </div>

                {{-- Resultado --}}
                <div>
                    <label class="block text-sm font-medium text-white">Resultado</label>
                    <div id="calc-result"
                        class="mt-1 text-2xl font-bold text-azul bg-white rounded-lg h-10 flex items-center px-3 shadow-inner">
                        €0.00
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <button id="calc-reset" type="button"
                    class="px-4 py-1.5 rounded-full bg-rojo text-white text-sm font-medium hover:bg-red-700 transition">
                    Reset
                </button>
            </div>
        </div>

        {{-- ========================= --}}
        {{-- TABLA DE TRADUCCIONES --}}
        {{-- ========================= --}}
        <div class="rounded-2xl shadow-xl overflow-hidden border border-beige bg-beige2">
            <div class="bg-azul text-beige2 px-6 py-4">
                <h3 class="font-semibold text-lg">Traducciones</h3>
            </div>
            <div class="rounded-lg border border-azul/20 overflow-hidden">
                <div class="overflow-x-auto">
                <table class="w-full text-sm text-azul">
                    <thead class="bg-beige/80 uppercase text-xs tracking-wide border-b border-beige">
                        <tr>
                            <th class="p-3 text-left">Fecha</th>
                            <th class="p-3 text-left">Nombre</th>
                            <th class="p-3 text-left">Email</th>
                            <th class="p-3 text-left">Idiomas</th>
                            <th class="p-3 text-left">Urgencia</th>
                            <th class="p-3 text-left">Archivo</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($items as $tr)
                            <tr class="odd:bg-beige2 even:bg-white hover:bg-azul/5 transition">
                                <td class="p-3">
                                    {{ $tr->created_at->format('d/m/Y H:i') }}
                                </td>

                                <td class="p-3">
                                    {{ $tr->name }}
                                </td>

                                <td class="p-3">
                                    <a href="mailto:{{ $tr->email }}"
                                        class="text-azul underline hover:text-rojo transition">
                                        {{ $tr->email }}
                                    </a>
                                </td>

                                <td class="p-3">
                                    <span class="px-2 py-1 rounded-full text-xs bg-azul text-beige2 font-semibold">
                                        {{ strtoupper($tr->source_lang) }}
                                    </span>
                                    <span class="mx-1 font-bold">→</span>
                                    <span class="px-2 py-1 rounded-full text-xs bg-rojo text-beige2 font-semibold">
                                        {{ strtoupper($tr->target_lang) }}
                                    </span>
                                </td>

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

                                <td class="p-3">
                                    <a href="{{ route('admin.translations.download', $tr->id) }}"
                                        class="px-3 py-1 rounded-full bg-azul text-beige2 text-xs font-medium hover:bg-rojo transition">
                                        Descargar
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>

                <div class="mt-6">
                    {{ $items->links() }}
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Traducciones</h2>
    </x-slot>
    <div class="py-6 max-w-5xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow sm:rounded-lg p-6">
            <div id="translation-calculator" class="mb-6 border rounded p-4 bg-gray-50">
                <h3 class="font-medium mb-2">Calculadora rápida de traducción</h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 items-end">
                    <div>
                        <label class="block text-xs text-gray-600">Número de palabras</label>
                        <input id="calc-words" type="number" min="0" step="1" value="0" class="mt-1 block w-full rounded border-gray-300" />
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600">Precio por palabra (€)</label>
                        <input id="calc-price" type="number" min="0" step="0.01" value="0.10" class="mt-1 block w-full rounded border-gray-300" />
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600">Resultado</label>
                        <div id="calc-result" class="mt-1 text-lg font-semibold">€0.00</div>
                    </div>
                </div>
                <div class="mt-3">
                    <button id="calc-reset" type="button" class="px-3 py-1 bg-gray-200 rounded text-sm">Reset</button>
                </div>
            </div>
            
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left">
                        <th class="p-2">Fecha</th>
                        <th class="p-2">Nombre</th>
                        <th class="p-2">Email</th>
                        <th class="p-2">Idiomas</th>
                        <th class="p-2">Urgencia</th>
                        <th class="p-2">Archivo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $tr)
                        <tr class="border-t">
                            <td class="p-2">{{ $tr->created_at->format('d/m/Y H:i') }}</td>
                            <td class="p-2">{{ $tr->name }}</td>
                            <td class="p-2">{{ $tr->email }}</td>
                            <td class="p-2">{{ $tr->source_lang }} → {{ $tr->target_lang }}</td>
                            <td class="p-2">{{ ucfirst($tr->urgency) }}</td>
                            <td class="p-2">
                                <a href="{{ route('admin.translations.download', $tr->id) }}"
                                    class="text-blue-600 hover:underline">
                                    Descargar
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4">{{ $items->links() }}</div>
        </div>
    </div>
</x-app-layout>
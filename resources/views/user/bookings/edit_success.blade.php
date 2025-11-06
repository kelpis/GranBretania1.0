<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Reserva actualizada</h2>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto">
        <div class="p-6 bg-green-50 border border-green-200 rounded">
            <h3 class="text-lg font-semibold mb-2">¡Tu reserva ha sido actualizada!</h3>
            <p class="text-sm text-green-800">
                Hemos guardado los cambios en tu reserva. Recibirás un correo con los detalles actualizados.
            </p>

            <div class="mt-4">
                <a href="{{ route('dashboard') }}"
                    class="inline-block px-4 py-2 bg-blue-600 text-white font-medium rounded hover:bg-blue-700">
                    Volver al panel
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
<section class="space-y-6">

    {{-- CABECERA DEL BLOQUE --}}
    <header class="bg-beige2 p-5 rounded-xl border border-beige shadow-sm">
        <h2 class="text-xl font-semibold text-azul">
            {{ __('Eliminar cuenta') }}
        </h2>

        <p class="mt-2 text-sm text-gray-700 leading-relaxed">
            {{ __('Una vez elimines tu cuenta, todos tus datos y recursos se borrarán de forma permanente. Si deseas conservar algo, descárgalo antes de continuar.') }}
        </p>
    </header>

    {{-- BOTÓN PRINCIPAL PELIGROSO --}}
    <div>
        <button
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
            class="bg-rojo text-beige2 px-4 py-2 rounded-lg font-semibold shadow hover:bg-red-700 transition">
            {{ __('Eliminar cuenta') }}
        </button>
    </div>

    {{-- MODAL DE CONFIRMACIÓN --}}
    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 space-y-5">
            @csrf
            @method('delete')

            {{-- TÍTULO DEL MODAL --}}
            <h2 class="text-xl font-semibold text-azul">
                {{ __('¿Seguro que quieres eliminar tu cuenta?') }}
            </h2>

            {{-- TEXTO EXPLICATIVO --}}
            <p class="text-sm text-gray-700 leading-relaxed">
                {{ __('Esta acción no se puede deshacer. Escribe tu contraseña para confirmar la eliminación definitiva de tu cuenta.') }}
            </p>

            {{-- CAMPO DE CONTRASEÑA --}}
            <div class="mt-4">
                <x-input-label for="password" value="{{ __('Contraseña') }}" class="text-azul" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-3/4 bg-white border-gray-300 text-azul rounded p-2 shadow-sm focus:ring-azul focus:border-azul"
                    placeholder="{{ __('Introduce tu contraseña') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2 text-rojo" />
            </div>

            {{-- BOTONES --}}
            <div class="flex justify-end gap-3 mt-6">

                {{-- CANCELAR --}}
                <button type="button"
                    x-on:click="$dispatch('close')"
                    class="px-4 py-2 rounded-lg bg-gray-100 text-negro border border-gray-300 hover:bg-gray-200 transition">
                    {{ __('Cancelar') }}
                </button>

                {{-- ELIMINAR --}}
                <button
                    class="px-4 py-2 rounded-lg bg-rojo text-beige2 font-semibold shadow hover:bg-red-700 transition">
                    {{ __('Eliminar definitivamente') }}
                </button>
            </div>

        </form>
    </x-modal>
</section>

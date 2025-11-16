<section class="space-y-6">

    {{-- CABECERA DEL BLOQUE --}}
    <header class="bg-beige2 p-5 rounded-xl border border-beige shadow-sm">
        <h2 class="text-xl font-semibold text-azul">
            {{ __('Actualizar contraseña') }}
        </h2>

        <p class="mt-2 text-sm text-gray-700 leading-relaxed">
            {{ __('Para mantener tu cuenta segura, utiliza una contraseña larga y difícil de adivinar.') }}
        </p>
    </header>

    {{-- FORMULARIO --}}
    <form method="post" action="{{ route('password.update') }}" class="bg-white p-6 rounded-xl shadow space-y-6 border border-beige">
        @csrf
        @method('put')

        {{-- CONTRASEÑA ACTUAL --}}
        <div>
            <x-input-label for="update_password_current_password" class="text-azul" :value="__('Contraseña actual')" />
            <x-text-input
                id="update_password_current_password"
                name="current_password"
                type="password"
                class="mt-1 block w-full bg-beige2 text-azul border-gray-300 rounded p-2 shadow-sm focus:ring-azul focus:border-azul"
                autocomplete="current-password"
            />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2 text-rojo" />
        </div>

        {{-- NUEVA CONTRASEÑA --}}
        <div>
            <x-input-label for="update_password_password" class="text-azul" :value="__('Nueva contraseña')" />
            <x-text-input
                id="update_password_password"
                name="password"
                type="password"
                class="mt-1 block w-full bg-beige2 text-azul border-gray-300 rounded p-2 shadow-sm focus:ring-azul focus:border-azul"
                autocomplete="new-password"
            />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2 text-rojo" />
        </div>

        {{-- CONFIRMAR --}}
        <div>
            <x-input-label for="update_password_password_confirmation" class="text-azul" :value="__('Confirmar contraseña')" />
            <x-text-input
                id="update_password_password_confirmation"
                name="password_confirmation"
                type="password"
                class="mt-1 block w-full bg-beige2 text-azul border-gray-300 rounded p-2 shadow-sm focus:ring-azul focus:border-azul"
                autocomplete="new-password"
            />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2 text-rojo" />
        </div>

        {{-- BOTÓN Y MENSAJE --}}
        <div class="flex items-center gap-4">
            <button
                class="px-4 py-2 bg-azul text-beige2 font-semibold rounded-lg shadow hover:bg-blue-900 transition">
                {{ __('Guardar cambios') }}
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-ok font-medium"
                >
                    {{ __('Guardado.') }}
                </p>
            @endif
        </div>
    </form>

</section>


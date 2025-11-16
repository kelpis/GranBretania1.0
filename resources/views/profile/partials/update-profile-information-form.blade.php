<section class="bg-beige2 p-6 rounded-2xl shadow-md border border-beige">

    {{-- HEADER --}}
    <header class="mb-6">
        <h2 class="text-2xl font-semibold text-azul">
            {{ __('Información del perfil') }}
        </h2>

        <p class="mt-1 text-sm text-gray-700">
            {{ __("Actualiza tu nombre y dirección de correo electrónico.") }}
        </p>
    </header>

    {{-- FORM DE VERIFICACIÓN --}}
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    {{-- FORM PRINCIPAL --}}
    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
            @method('patch')

        {{-- NAME --}}
        <div>
            <x-input-label for="name" :value="__('Nombre')" class="text-azul font-semibold"/>
            <x-text-input id="name" name="name" type="text"
                class="mt-1 block w-full bg-white text-azul border border-azul/30 rounded-lg p-2 focus:border-azul focus:ring-azul"
                :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2 text-red-600" :messages="$errors->get('name')" />
        </div>

        {{-- EMAIL --}}
        <div>
            <x-input-label for="email" :value="__('Correo electrónico')" class="text-azul font-semibold"/>
            <x-text-input id="email" name="email" type="email"
                class="mt-1 block w-full bg-white text-azul border border-azul/30 rounded-lg p-2 focus:border-azul focus:ring-azul"
                :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2 text-red-600" :messages="$errors->get('email')" />

            {{-- EMAIL NO VERIFICADO --}}
            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2 bg-info/20 p-3 rounded-lg border border-info/40">
                    <p class="text-sm text-negro">
                        {{ __('Tu correo electrónico no está verificado.') }}

                        <button form="send-verification"
                            class="underline text-sm text-azul hover:text-rojo ml-1">
                            {{ __('Haz clic aquí para reenviar el correo de verificación.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-ok">
                            {{ __('Se ha enviado un nuevo enlace de verificación.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        {{-- BOTONES --}}
        <div class="flex items-center gap-4">
            <button class="px-5 py-2 rounded-lg bg-azul text-beige2 hover:bg-rojo transition font-medium shadow">
                {{ __('Guardar cambios') }}
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-ok font-semibold"
                >
                    {{ __('Guardado') }}
                </p>
            @endif
        </div>
    </form>
</section>

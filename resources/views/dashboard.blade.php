@extends('layouts.site')

@section('title', 'Area Personal · Gran Bretania')
@section('header')
    @include('layouts.navigation')
@endsection


@section('content')
    <section class="py-12"
             style="background-image: url('{{ asset('images/libros-ingles(3).jpg') }}'); background-size: cover; background-position: center;">
        <div class="container mx-auto px-4 max-w-3xl">

            <div class="rounded-2xl shadow p-8 border border-beige" style="background-color: rgba(255,255,255,0.9);">


                <h1 class="text-azul text-3xl font-semibold mb-3">
                    ¡Hola {{ auth()->user()->name }}!
                </h1>

                <p class="text-gray-700 text-base leading-relaxed">
                    Bienvenido de nuevo a <span class="font-semibold">Gran Bretania</span>.
                    Desde aquí puedes gestionar tus clases reservadas, tus solicitudes de traducción
                    y tu información personal.
                </p>

                <div class="grid sm:grid-cols-3 gap-6 mt-10">

                    {{-- RESERVAS --}}
                    <a href="{{ route('user.bookings.index') }}"
                        class="p-5 rounded-xl bg-azul text-beige2 hover:bg-blue-900 shadow transition block text-center">
                        <h3 class="font-semibold text-lg mb-1">Mis clases</h3>
                        <p class="text-sm opacity-80">Ver & gestionar reservas</p>
                    </a>

                    {{-- TRADUCCIONES --}}
                    <a href="{{ route('user.translations.index') }}"
                        class="p-5 rounded-xl bg-beige text-azul hover:bg-beige/80 shadow transition block text-center">
                        <h3 class="font-semibold text-lg mb-1">Mis traducciones</h3>
                        <p class="text-sm opacity-80">Solicitudes enviadas</p>
                    </a>

                    {{-- PERFIL --}}
                    <a href="{{ route('profile.edit') }}"
                        class="p-5 rounded-xl bg-rojo text-beige2 hover:bg-red-700 shadow transition block text-center">
                        <h3 class="font-semibold text-lg mb-1">Mi perfil</h3>
                        <p class="text-sm opacity-80">Actualizar datos</p>
                    </a>

                </div>

            </div>
        </div>
    </section>

@endsection
@extends('layouts.site')

@section('title', '419 · Página expirada')

@section('content')
<section class="py-24">
    <div class="container mx-auto px-4 max-w-2xl">
        <div class="bg-white rounded-2xl shadow p-8 border border-beige text-center">
            <div class="text-azul text-6xl font-bold">419</div>
            <h1 class="text-2xl font-semibold mt-4 text-azul">Página expirada</h1>
            <p class="mt-4 text-gray-700">La sesión ha caducado o el token CSRF no es válido. Intenta enviar el formulario de nuevo.</p>
            <div class="mt-6 flex justify-center gap-4">
                <a href="{{ url()->previous() ?: route('home') }}" class="btn-primary">Volver</a>
                <a href="{{ route('home') }}" class="btn-secondary">Ir al inicio</a>
            </div>
        </div>
    </div>
</section>
@endsection

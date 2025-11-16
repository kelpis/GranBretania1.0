@extends('layouts.site')

@section('title', '403 · Acceso denegado')

@section('content')
<section class="py-24">
    <div class="container mx-auto px-4 max-w-2xl">
        <div class="bg-white rounded-2xl shadow p-8 border border-beige text-center">
            <div class="text-azul text-6xl font-bold">403</div>
            <h1 class="text-2xl font-semibold mt-4 text-azul">Acceso denegado</h1>
            <p class="mt-4 text-gray-700">No tienes permiso para acceder a este recurso.</p>
            <div class="mt-6 flex justify-center gap-4">
                <a href="{{ route('home') }}" class="btn-primary">Ir al inicio</a>
                <a href="{{ route('contact.create') }}" class="btn-secondary">Solicitar ayuda</a>
            </div>
        </div>
    </div>
</section>
@endsection

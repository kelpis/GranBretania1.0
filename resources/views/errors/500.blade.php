@extends('layouts.site')

@section('title', '500 · Error del servidor')

@section('content')
<section class="py-24">
    <div class="container mx-auto px-4 max-w-2xl">
        <div class="bg-white rounded-2xl shadow p-8 border border-beige text-center">
            <div class="text-azul text-6xl font-bold">500</div>
            <h1 class="text-2xl font-semibold mt-4 text-azul">Error interno del servidor</h1>
            <p class="mt-4 text-gray-700">Ha ocurrido un problema en el servidor. Estamos trabajando para solucionarlo.</p>
            <div class="mt-6 flex justify-center gap-4">
                <a href="{{ route('home') }}" class="btn-primary">Ir al inicio</a>
                <a href="{{ route('contact.create') }}" class="btn-secondary">Informar del problema</a>
            </div>
        </div>
    </div>
</section>
@endsection

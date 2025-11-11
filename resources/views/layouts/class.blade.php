@extends('layouts.site')

@section('title', 'Clases · Gran Bretania')

@section('content')
    <div class="container mx-auto px-4 py-12">
        <h1 class="text-3xl font-semibold text-azul mb-4">Clases</h1>
        <p class="mb-6">En Gran Bretania ofrecemos clases personalizadas de inglés para todos los niveles: conversación, preparación de exámenes, inglés profesional y refuerzo general.</p>

        <div class="grid md:grid-cols-2 gap-6">
            <div class="card p-6">
                <h2 class="font-semibold mb-2">Clases individuales</h2>
                <p class="opacity-80">Sesiones one-to-one adaptadas a tus objetivos y ritmo.</p>
            </div>
            <div class="card p-6">
                <h2 class="font-semibold mb-2">Clases en grupo</h2>
                <p class="opacity-80">Grupos reducidos con enfoque comunicativo y dinámico.</p>
            </div>
        </div>

        <div class="mt-8">
            <a href="{{ route('contact.create') }}" class="btn-primary">Reservar clase</a>
        </div>
    </div>
@endsection

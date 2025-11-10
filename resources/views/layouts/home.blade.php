@extends('layouts.site')

@section('title', 'Inicio · Gran Bretania')

@section('content')
    <section class="container mx-auto px-4 py-14 text-center">
        <h1 class="bg-azul text-beige2 inline-block px-8 py-4 rounded-xl">
            Formación y traducción con las misma pasión por el idioma
        </h1>
        {{-- BLOQUE: Clases de inglés --}}
        <section id="clases" class="bg-beige2 py-16">
            <div class="container mx-auto px-4 text-left">
                {{-- Título encima del contenido (parrafo + imagen) --}}
                <h2 class="text-azul text-left md:text-left mb-8">Clases de inglés personalizadas</h2>

            <div class="grid md:grid-cols-2 gap-10 items-stretch">

                {{-- Texto --}}
                <div class="h-full flex flex-col justify-start min-h-0">
                    <p class="mt-4 text-left md:text-left">
                        En <span class="font-semibold">Gran Bretania</span> las clases de inglés se adaptan a ti.
                        Con un enfoque práctico y cercano, aprenderás a comunicarte con seguridad desde el primer día.
                        Trabajamos con una metodología flexible que combina conversación, gramática aplicada y recursos
                        personalizados según tus objetivos.
                    </p>

                    {{-- Tipos de clases (resumen) --}}
                    <ul class="mt-6 grid sm:grid-cols-2 gap-4 text-[16px]">
                        <li class="card flex items-start gap-3 bg-azul text-beige2 rounded-card p-6">
                            <span aria-hidden="true">🗣️</span>
                            <div>
                                <h3 class="font-semibold">Conversación práctica</h3>
                                <p class="opacity-80">Gana fluidez y naturalidad al hablar.</p>
                            </div>
                        </li>
                        <li class="card flex items-start gap-3 bg-azul text-beige2 rounded-card p-6">
                            <span aria-hidden="true">🎯</span>
                            <div>
                                <h3 class="font-semibold">Preparación de exámenes</h3>
                                <p class="opacity-80">Cambridge, IELTS u objetivos académicos.</p>
                            </div>
                        </li>
                        <li class="card flex items-start gap-3 bg-azul text-beige2 rounded-card p-6">
                            <span aria-hidden="true">💼</span>
                            <div>
                                <h3 class="font-semibold">Inglés profesional</h3>
                                <p class="opacity-80">Trabajo, presentaciones y entrevistas.</p>
                            </div>
                        </li>
                        <li class="card flex items-start gap-3 bg-azul text-beige2 rounded-card p-6">
                            <span aria-hidden="true">📚</span>
                            <div>
                                <h3 class="font-semibold">Refuerzo general</h3>
                                <p class="opacity-80">Comprensión, escritura y gramática.</p>
                            </div>
                        </li>
                    </ul>

                    {{-- CTA --}}
                    <div class="mt-8">
                        <a class="btn-secondary">Reservar clase</a>
                    </div>
                </div>

                {{-- Imagen / ilustración (opcional) --}}
                    <div class="order-first md:order-none h-full">
                    <img src="{{ asset('images/alumnoOnline.png') }}" alt="Clase de inglés personalizada online"
                        class="w-full h-full object-cover rounded-card shadow-sm">
                </div>
            </div>
        </section>

        <div class="mt-8 flex items-center justify-center gap-4">
            <a class="btn-primary">Reservar clase</a>
            <a class="btn-secondary">Solicitar traducción</a>
        </div>
    </section>

    <section class="bg-beige2 py-12">
        <div class="container mx-auto px-4 grid md:grid-cols-3 gap-6">
            <div class="card">
                <h3 class="mb-2">Clases</h3>
                <p>Dinámicas, objetivos claros y horarios flexibles.</p>
            </div>
            <div class="card">
                <h3 class="mb-2">Traducciones</h3>
                <p>Documentos en PDF/DOCX con entrega pactada.</p>
            </div>
            <div class="card">
                <h3 class="mb-2">Opiniones</h3>
                <p>La confianza de nuestros alumnos nos avala.</p>
            </div>
        </div>
    </section>
@endsection
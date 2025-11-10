@extends('layouts.site')

@section('title', 'Inicio · Gran Bretania')

@section('content')
    <section class="container mx-auto px-4 py-14 text-center">
        <h1 class="bg-azul text-beige2 inline-block px-8 py-4 rounded-xl">
            Formación y traducción con las misma pasión por el idioma
        </h1>
        {{-- BLOQUE: Clases de inglés --}}
        <section id="clases" class="bg-beige2 py-16 mt-[100px]">
            <div class="container mx-auto px-4 text-left">
                {{-- Título encima del contenido (parrafo + imagen) --}}
                <h2 class="text-azul text-left md:text-left mb-8">Clases de inglés online</h2>

                <div class="grid md:grid-cols-2 gap-10 items-stretch">

                    {{-- Texto --}}
                    <div class="h-full flex flex-col justify-start min-h-0">
                        <div class="w-full max-w-2xl mx-auto">
                            <p class="mt-0 text-left md:text-left">
                                En <span class="font-semibold">Gran Bretania</span> las clases de inglés se adaptan a ti.
                                Con un enfoque práctico y cercano, aprenderás a comunicarte con seguridad desde el primer
                                día.
                                Trabajamos con una metodología flexible que combina conversación, gramática aplicada y
                                recursos
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
                            <div class="mt-8 flex flex-col sm:flex-row sm:justify-evenly items-center gap-4">
                                <a class="btn-secondary">Reservar clase</a>
                                <a class="btn-secondary">Saber más</a>
                            </div>
                        </div>
                    </div>

                    {{-- Imagen / ilustración (opcional) --}}
                    <div class="order-first md:order-none h-full">
                        <img src="{{ asset('images/alumnoOnline.png') }}" alt="Clase de inglés personalizada online"
                            class="w-full h-full object-cover rounded-card shadow-sm">
                    </div>
                </div>
        </section>





        <section
            class="relative py-16 text-center transform-gpu transition-transform duration-200 hover:scale-105 bg-cover bg-center rounded-xl overflow-hidden mt-[50px]"
            style="background-image: url('{{ asset('images/learn-english.jpg') }}')">
            <div class="absolute inset-0 bg-black/40" aria-hidden="true"></div>
            <div class="container mx-auto px-6 md:px-8 relative z-10 text-left">
                <h2 class="text-3xl md:text-4xl font-semibold text-azul mb-4 text-left">
                    ¿Tu primera vez?
                </h2>
                <p class="text-beige2 text-lg max-w-2xl mb-8 leading-relaxed">
                    Empieza con una clase gratuita para conocer tu nivel y objetivos.
                </p>
                <div class="flex flex-col sm:flex-row sm:justify-start items-start gap-4">
                    <a href="{{ route('contact.create') . '?subject=' . urlencode('Clase de prueba gratuita') }}"
                        class="inline-block bg-beige2 text-azul font-semibold px-8 py-3 rounded-xl shadow-md hover:bg-rojo hover:text-white hover:shadow-lg transition">
                        Solicitar clase de prueba
                    </a>
                </div>
            </div>
        </section>



    </section>

    {{-- BLOQUE: Traducciones --}}
    <section id="traducciones" class="bg-beige2 py-16">
        <div class="container mx-auto px-4">
            {{-- Coloca el título alineado con la columna de texto --}}
            <div class="grid md:grid-cols-2">
                <div></div>
                <div>
                    <h2 class="text-azul mb-6 max-w-2xl">Traducciones profesionales</h2>
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-10 items-stretch">

                {{-- Imagen / ilustración (opcional) --}}
                <div class="h-full">
                    <img src="{{ asset('images/definicion.jpg') }}" alt="Servicio de traducciones profesionales"
                        class="w-full h-full object-cover rounded-card shadow-sm">
                </div>

                {{-- Texto (p + lista) --}}
                <div class="h-full">
                    <div class="flex flex-col h-full">
                        <div class="flex-1 max-w-2xl">
                            <p>
                                En <span class="font-semibold">Gran Bretania</span> ofrecemos traducciones precisas,
                                naturales y
                                adaptadas al contexto.
                                Cada encargo se realiza con atención al detalle y total confidencialidad, garantizando un
                                resultado
                                fiel al
                                significado y tono original del texto.
                            </p>

                            {{-- Tipos de traducción (resumen) --}}
                            <ul class="mt-6 grid sm:grid-cols-2 gap-4 text-[16px]">
                                <li class="card flex items-start gap-3 bg-beige">
                                    <span aria-hidden="true">⚖️</span>
                                    <div>
                                        <h3 class="font-semibold">Jurídica</h3>
                                        <p class="opacity-80">Contratos, documentos legales y certificados.</p>
                                    </div>
                                </li>
                                <li class="card flex items-start gap-3 bg-beige">
                                    <span aria-hidden="true">💊</span>
                                    <div>
                                        <h3 class="font-semibold">Médica</h3>
                                        <p class="opacity-80">Informes clínicos y documentación sanitaria.</p>
                                    </div>
                                </li>
                                <li class="card flex items-start gap-3 bg-beige">
                                    <span aria-hidden="true">🎓</span>
                                    <div>
                                        <h3 class="font-semibold">Académica</h3>
                                        <p class="opacity-80">Artículos, proyectos y trabajos de investigación.</p>
                                    </div>
                                </li>
                                <li class="card flex items-start gap-3 bg-beige">
                                    <span aria-hidden="true">🎬</span>
                                    <div>
                                        <h3 class="font-semibold">Audiovisual</h3>
                                        <p class="opacity-80">Subtitulación y guiones adaptados al público objetivo.</p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- CTA por debajo del grid, alineada con la columna de texto --}}
            <div class="grid md:grid-cols-2">
                <div></div>
                <div class="mt-8">
                    <div class="max-w-2xl ml-auto flex justify-evenly items-center">
                        <a class="btn-primary">Solicitar traducción</a>
                        <a class="btn-secondary">Ver más información</a>
                    </div>
                </div>
            </div>
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
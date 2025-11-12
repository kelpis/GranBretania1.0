@extends('layouts.site')

@section('title', 'Inicio · Gran Bretania')
@section('header')

    <header class="py-4">
        <div class="hidden lg:block text-right pl-4 pr-8">
            <a href="{{ route('login') }}" class="btn-secondary text-beige2 !py-2 !px-4">Acceder</a>
        </div>
        <div class="container mx-auto px-4 text-center">

            <a href="{{ route('home') }}" class="inline-block">
                <img src="{{ asset('images/logoSinMargen.png') }}" alt="Gran Bretania" class="mx-auto h-64 md:h-80 w-auto">
            </a>


            <nav class="my-8 flex flex-wrap items-center justify-center gap-16 text-azul tracking-wider">
                <a href="{{ route('home') }}" class="hover:underline text-lg md:text-xl font-medium px-2">Inicio</a>
                <a href="{{ route('clases') }}" class="hover:underline text-lg md:text-xl font-medium px-2">Clases</a>
                <a href="{{ route('traducciones') }}"
                    class="hover:underline text-lg md:text-xl font-medium px-2">Traducciones</a>
                <a href="{{ route('sobremi') }}" class="hover:underline text-lg md:text-xl font-medium px-2">Sobre mí</a>
                <a href="{{ route('faq') }}" class="hover:underline text-lg md:text-xl font-medium px-2">FAQ</a>
                <a href="{{ route('contact.create') }}"
                    class="hover:underline text-lg md:text-xl font-medium px-2">Contacto</a>
            </nav>
        </div>
    </header>
@endsection
@section('content')

    <section class="container mx-auto px-4 pt-0 pb-14 text-center">
        <h1 class="mt-0 bg-azul text-beige2 inline-block px-8 py-4 rounded-xl">
            Enseñanza de inglés y traducciones
        </h1>
        {{-- BLOQUE: Clases de inglés --}}
    <section id="clases" class="bg-beige2 py-16 mt-24">
            <div class="container mx-auto px-4 text-left">

                <h2 class="text-azul mb-8">Clases de inglés online</h2>

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
                            <ul class="mt-6 grid sm:grid-cols-2 gap-4 text-base">
                                <li class="card flex items-start gap-3 bg-azul text-beige2 rounded-card p-6">
                                    <span aria-hidden="true">🗣️</span>
                                    <div>
                                        <h3 class="font-semibold mb-2">Conversación práctica</h3>
                                        <p class="opacity-80 leading-snug">Gana fluidez y naturalidad al hablar.</p>
                                    </div>
                                </li>
                                <li class="card flex items-start gap-3 bg-azul text-beige2 rounded-card p-6">
                                    <span aria-hidden="true">🎯</span>
                                    <div>
                                        <h3 class="font-semibold mb-2">Preparación de exámenes</h3>
                                        <p class="opacity-80 leading-snug">Cambridge, IELTS u objetivos académicos.</p>
                                    </div>
                                </li>
                                <li class="card flex items-start gap-3 bg-azul text-beige2 rounded-card p-6">
                                    <span aria-hidden="true">💼</span>
                                    <div>
                                        <h3 class="font-semibold mb-2">Inglés profesional</h3>
                                        <p class="opacity-80 leading-snug">Trabajo, presentaciones y entrevistas.</p>
                                    </div>
                                </li>
                                <li class="card flex items-start gap-3 bg-azul text-beige2 rounded-card p-6">
                                    <span aria-hidden="true">📚</span>
                                    <div>
                                        <h3 class="font-semibold mb-2">Refuerzo general</h3>
                                        <p class="opacity-80 leading-snug">Comprensión, escritura y gramática.</p>
                                    </div>
                                </li>
                            </ul>

                            {{-- CTA --}}
                            <div class="mt-8 flex flex-col sm:flex-row sm:justify-evenly items-center gap-4">
                                <a class="btn-primary">Reservar clase</a>
                                <a href="{{ route('clases') }}" class="btn-secondary">Saber más</a>
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


        {{-- BLOQUE: Por qué elegirnos (Home) --}}
    <section class="bg-beige2 py-16 mt-6">
            <div class="container mx-auto px-4 text-center">
                <h2 class="text-azul">¿Por qué elegirnos?</h2>
                <p class="mt-2 opacity-80">Tres razones para empezar hoy mismo</p>

                <div class="mt-10 grid md:grid-cols-3 gap-10">
                    <div>
                        <div class="mx-auto h-16 w-16 flex items-center justify-center rounded-full border-2 border-azul">
                            <span class="text-3xl" aria-hidden="true">🌐</span>
                        </div>
                        <h3 class="mt-5 font-semibold tracking-wide">Totalmente online</h3>
                        <p class="mt-2 opacity-80">Clases flexibles según tu disponibilidad.</p>
                    </div>
                    {{-- clamp font-size --}}
                    <div>
                        <div class="mx-auto h-16 w-16 flex items-center justify-center rounded-full border-2 border-azul">
                            <span class="text-3xl" aria-hidden="true">💬</span>
                        </div>
                        <h3 class="mt-5 font-semibold tracking-wide">Seguimiento personalizado</h3>
                        <p class="mt-2 opacity-80">Plan y feedback adaptados a tus objetivos.</p>
                    </div>

                    <div>
                        <div class="mx-auto h-16 w-16 flex items-center justify-center rounded-full border-2 border-azul">
                            <span class="text-3xl" aria-hidden="true">📘</span>
                        </div>
                        <h3 class="mt-5 font-semibold tracking-wide">Material actualizado</h3>
                        <p class="mt-2 opacity-80">Recursos actuales y prácticos para progresar.</p>
                    </div>
                </div>
            </div>
        </section>


        <section
            class="relative py-16 text-center transform-gpu transition-transform duration-200 hover:scale-105 bg-cover bg-center rounded-xl overflow-hidden mt-24"
            style="background-image: url('{{ asset('images/learn-english.jpg') }}')">
            <!-- Gradient overlay: aún más claro para un aspecto luminoso y aireado -->
            <div class="absolute inset-0" aria-hidden="true"
                style="background: linear-gradient(180deg, rgba(0,0,0,0.08) 0%, rgba(0,0,0,0.04) 40%, rgba(0,0,0,0.18) 100%);">
            </div>
            <div class="container mx-auto px-6 md:px-8 relative z-10 text-center">
                <!-- Text background: degradado muy claro para mantener contraste sin oscurecer demasiado -->
                <div
                    class="inline-block px-6 py-6 rounded-2xl bg-gradient-to-r from-black/20 via-black/05 to-transparent backdrop-blur-sm">
                    <h2 class="text-3xl md:text-4xl font-semibold text-white mb-4">
                        ¿Tu primera vez?
                    </h2>
                    <p class="text-beige2 text-lg max-w-2xl mx-auto mb-8 leading-relaxed">
                        Empieza con una clase gratuita para conocer tu nivel y objetivos.
                    </p>
                    <div class="flex flex-col sm:flex-row sm:justify-center items-center gap-4">
                        <a href="{{ route('contact.create') . '?subject=' . urlencode('Clase de prueba gratuita') }}"
                            class="inline-block bg-beige2 text-azul font-semibold px-8 py-3 rounded-xl shadow-md hover:bg-rojo hover:text-white hover:shadow-lg transition">
                            Solicitar clase de prueba
                        </a>
                    </div>
                </div>
            </div>
        </section>



    </section>

    {{-- BLOQUE: Traducciones --}}
    <section id="traducciones" class="bg-beige2 py-16 mt-24">
        <div class="container mx-auto px-4">

            <div class="grid md:grid-cols-2">
                <div></div>
                <div>
                    <h2 class="text-azul mb-6 max-w-2xl">Traducciones profesionales</h2>
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-10 items-stretch">

                {{-- Imagen--}}
                <div class="h-full">
                    <img src="{{ asset('images/definicion.jpg') }}" alt="Servicio de traducciones profesionales"
                        class="w-full h-full object-cover rounded-card shadow-sm">
                </div>

                {{-- Texto --}}
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
                            <ul class="mt-6 grid sm:grid-cols-2 gap-4 text-base">
                                <li class="card flex items-start gap-3 bg-beige">
                                    <span aria-hidden="true">⚖️</span>
                                    <div>
                                        <h3 class="font-semibold mb-2">Jurídica</h3>
                                        <p class="opacity-80 leading-snug">Contratos, documentos legales y certificados.</p>
                                    </div>
                                </li>
                                <li class="card flex items-start gap-3 bg-beige">
                                    <span aria-hidden="true">💊</span>
                                    <div>
                                        <h3 class="font-semibold mb-2">Médica</h3>
                                        <p class="opacity-80 leading-snug">Informes clínicos y documentación sanitaria.</p>
                                    </div>
                                </li>
                                <li class="card flex items-start gap-3 bg-beige">
                                    <span aria-hidden="true">🎓</span>
                                    <div>
                                        <h3 class="font-semibold mb-2">Académica</h3>
                                        <p class="opacity-80 leading-snug">Artículos, proyectos y trabajos de investigación.
                                        </p>
                                    </div>
                                </li>
                                <li class="card flex items-start gap-3 bg-beige">
                                    <span aria-hidden="true">🎬</span>
                                    <div>
                                        <h3 class="font-semibold mb-2">Audiovisual</h3>
                                        <p class="opacity-80 leading-snug">Subtitulación y guiones adaptados al público
                                            objetivo.</p>
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
                        <a href="{{ route('traducciones') }}" class="btn-secondary">Ver más información</a>
                    </div>
                </div>
            </div>
        </div>
    </section>




    <section id="empresas" class="relative py-20 text-white overflow-hidden mt-24">
        {{-- Imagen de fondo --}}

        <div class="absolute inset-0">
            <img src="{{ asset('images/empresarios.jpg') }}" alt="Solución integral en inglés para empresas"
                class="w-full h-full object-cover brightness-90">
        </div>

        {{-- Capa azul translúcida (suave) --}}
        <div class="absolute inset-0 bg-gradient-to-r from-azul/60 via-azul/40 to-transparent"></div>


        {{-- Contenido principal --}}
        <div class="relative container mx-auto px-4">
            <div class="text-left max-w-4xl">
                <h2 class="text-3xl md:text-4xl font-semibold mb-4">Solución integral para tu empresa</h2>
                <p class="mt-3 text-white/90 leading-relaxed">
                    Traducciones especializadas, interpretación en tiempo real y formación en inglés profesional para
                    equipos.<br>
                    Un único proveedor, procesos ágiles y resultados medibles.
                </p>

            </div>

            {{-- Servicios clave --}}
            <div class="mt-10 grid md:grid-cols-3 gap-6">
                <article class="bg-black/40 hover:bg-black/50 rounded-xl p-6 backdrop-blur-sm transition">
                    <h3 class="font-semibold text-xl text-white">Traducción especializada</h3>
                    <p class="mt-2 text-white/90 leading-snug">
                        Jurídica, médica, académica y audiovisual. Terminología precisa, control de calidad y entregas
                        puntuales.
                    </p>
                    <ul class="mt-3 text-sm text-white/80 list-disc ml-5">
                        <li>Memorias y glosarios de empresa</li>
                        <li>Revisión y maquetación</li>
                    </ul>
                    <a href="{{ route('traducciones') }}" class="mt-4 inline-block btn-secondary">
                        Ver traducciones →
                    </a>
                </article>

                <article class="bg-black/40 hover:bg-black/50 rounded-xl p-6 backdrop-blur-sm transition">
                    <h3 class="font-semibold text-xl text-white">Interpretación</h3>
                    <p class="mt-2 text-white/90 leading-snug">
                        Consecutiva o simultánea para reuniones, webinars y eventos online. Comunicación fluida entre
                        equipos y clientes.
                    </p>
                    <ul class="mt-3 text-sm text-white/80 list-disc ml-5">
                        <li>Briefing previo y guía terminológica</li>
                        <li>Soporte técnico de sala virtual</li>
                    </ul>
                    <a href="{{ route('contact.create') }}" class="mt-4 inline-block btn-secondary">
                        Solicitar intérprete →
                    </a>
                </article>

                <article class="bg-black/40 hover:bg-black/50 rounded-xl p-6 backdrop-blur-sm transition">
                    <h3 class="font-semibold text-xl text-white">Formación in-company</h3>
                    <p class="mt-2 text-white/90 leading-snug">
                        Inglés profesional para equipos: reuniones, presentaciones, email y entrevistas. Programas a medida.
                    </p>
                    <ul class="mt-3 text-sm text-white/80 list-disc ml-5">
                        <li>Diagnóstico de nivel y objetivos</li>
                        <li>KPIs de progreso y reportes</li>
                    </ul>
                    <a href="{{ route('clases') }}" class="mt-4 inline-block btn-secondary">
                        Ver formación →
                    </a>
                </article>
            </div>


        </div>
    </section>


    <section class="bg-beige2 py-16 mt-24">
        <div class="container mx-auto px-4 grid md:grid-cols-2 gap-10 items-center">
            <img src="{{ asset('images/profesora.jpg') }}" alt="Tania Morais Villar"
                class="rounded-xl shadow-md object-cover h-80 w-full">
            <div>
                <h2 class="text-azul text-3xl font-semibold mb-4">Sobre mí</h2>
                <p class="text-gray-700 leading-relaxed">
                    Soy Tania, profesora de inglés y traductora profesional.
                    En <strong>Gran Bretania</strong> combino años de experiencia docente con una atención personalizada,
                    adaptando cada clase o proyecto a las necesidades de mis alumnos y clientes.
                </p>
                <a href="{{ route('sobremi') }}" class="btn-secondary mt-6 inline-block">Conóceme mejor</a>
            </div>
        </div>
    </section>


    {{-- BLOQUE: Opiniones --}}
    <section id="opiniones" class="bg-beige py-16 mt-24">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-azul mb-10">Opiniones de nuestros alumnos y clientes</h2>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
                {{-- Opinión 1 --}}
                <div class="card bg-white">
                    <div class="flex flex-col items-center text-center">
                        <span class="text-5xl text-rojo mb-4">“</span>
                        <p class="italic text-base">
                            Las clases con Tania me ayudaron a ganar confianza hablando en inglés.
                            El ambiente es cercano y muy profesional.
                        </p>
                        <p class="mt-4 font-semibold text-azul">María L.</p>
                        <p class="text-sm opacity-80">Estudiante de conversación</p>
                    </div>
                </div>

                {{-- Opinión 2 --}}
                <div class="card bg-white">
                    <div class="flex flex-col items-center text-center">
                        <span class="text-5xl text-rojo mb-4">“</span>
                        <p class="italic text-base">
                            Encargué una traducción médica y quedé encantada con la precisión
                            y rapidez. Muy recomendable.
                        </p>
                        <p class="mt-4 font-semibold text-azul">Laura G.</p>
                        <p class="text-sm opacity-80">Cliente de traducción</p>
                    </div>
                </div>

                {{-- Opinión 3 --}}
                <div class="card bg-white">
                    <div class="flex flex-col items-center text-center">
                        <span class="text-5xl text-rojo mb-4">“</span>
                        <p class="italic text-base">
                            Las clases online son dinámicas y se adaptan a mis horarios.
                            Aprender inglés así da gusto.
                        </p>
                        <p class="mt-4 font-semibold text-azul">David R.</p>
                        <p class="text-sm opacity-80">Alumno de inglés profesional</p>
                    </div>
                </div>
            </div>
        </div>
    </section>





@endsection
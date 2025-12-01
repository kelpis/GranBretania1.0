{{--
Layout: home.blade.php
Propósito: página principal (hero, carrusel, CTA, secciones resumen).
Notas: carga contenido dinámico y componentes Alpine; mantener imágenes optimizadas.
--}}

@extends('layouts.site')

@section('title', 'Inicio · Gran Bretania')
{{-- Use the layout header from `layouts.site` to avoid duplicate navs (desktop + mobile). --}}
@section('content')


    {{-- -CARRUSEL --}}
    <section x-data="{
                                                                                    images: [
                                                                                        '{{ asset('images/edimburgo.jpg') }}',
                                                                                        '{{ asset('images/londres.jpg') }}',
                                                                                        '{{ asset('images/edimburgo2.jpg') }}',
                                                                                    ],
                                                                                   current: 0,
                                                                        fading: false,
                                                                        next() {
                                                                            this.fading = true;
                                                                            setTimeout(() => {
                                                                                this.current = (this.current + 1) % this.images.length;
                                                                                this.fading = false;
                                                                            }, 1500);
                                                                        },
                                                                        init() {
                                                                            setInterval(() => this.next(), 7000);
                                                                        }
                                                                    }" class="container mx-auto px-4 mt-6">
        <div class="relative rounded-2xl overflow-hidden bg-center bg-cover
                                                                                h-[360px] md:h-[460px] lg:h-[520px]"
            :style="`background-image: url('${images[current]}')`">

            {{-- overlay suave sobre la foto --}}
            <div class="absolute inset-0 bg-black/20 dark:bg-black/40
                                                                                    transition-opacity duration-[1500ms]"
                :class="fading ? 'opacity-0' : 'opacity-100'">
            </div>

            {{-- tarjeta centrada con logo + título --}}
            <div class="absolute inset-0 bg-black/10 dark:bg-black/40"></div>

            {{-- Logo layer: responsive — static on small, absolute on md+ to avoid overlap --}}
            <div class="md:absolute z-30 left-6 md:left-10 top-6 md:top-12 relative flex justify-center md:justify-start">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('images/logoMonocromadoSinMARGEN.png') }}" alt="Gran Bretania"
                        class="h-44 md:h-64 lg:h-72 w-auto drop-shadow-[0_12px_28px_rgba(0,0,0,0.6)] dark:invert dark:brightness-0">
                </a>
            </div>

            {{-- Text layer: left aligned, pinned to bottom --}}
            <div class="absolute z-10 left-4 bottom-6 md:left-8 md:bottom-12">
                <div
                    class="rounded-2xl px-6 py-5 bg-white/30 dark:bg-slate-800/60 backdrop-blur-sm shadow-xl border border-white/20 dark:border-white/10 max-w-md">
                    <p class="text-xs md:text-sm tracking-[0.25em] uppercase text-black/70 dark:text-white/80 mb-1">Academia
                        de inglés · Traducción</p>
                    <h2 class="text-lg md:text-2xl font-semibold text-black dark:text-white">Enseñanza de inglés y
                        traducciones</h2>
                </div>
            </div>
        </div>
    </section>



    <section x-data="{
                                                        images: [
                                                            '{{ asset('images/edimburgo.jpg') }}',
                                                            '{{ asset('images/london.jpg') }}',
                                                            '{{ asset('images/londres.jpg') }}',
                                                        ],
                                                        current: 0,
                                                        fading: false,
                                                        next() {
                                                            this.fading = true;
                                                            setTimeout(() => {
                                                                this.current = (this.current + 1) % this.images.length;
                                                                this.fading = false;
                                                            }, 900);
                                                        },
                                                        init() {
                                                            setInterval(() => this.next(), 7000);
                                                        }
                                                    }" x-init="init" class="container mx-auto px-4 mt-8">
        <div class="relative rounded-2xl overflow-hidden bg-center bg-cover
                                                                h-[360px] md:h-[460px] lg:h-[520px]"
            :style="`background-image: url('${images[current]}')`">

            {{-- overlay suave --}}
            <div class="absolute inset-0 bg-black/20 dark:bg-black/40
                                                                    transition-opacity duration-[900ms]"
                :class="fading ? 'opacity-0' : 'opacity-100'">
            </div>

            {{-- BLOQUE TRANSPARENTE A LA IZQUIERDA --}}
            <div class="relative z-10 h-full flex items-center px-6 md:px-12">
                <div class="flex flex-col items-center gap-4 max-w-xl w-full">

                    {{-- LOGO CENTRADO SOBRE EL TEXTO CON HALO CUADRADO --}}
                    <div class="relative flex justify-center w-full">

                        {{-- CONTENEDOR DEL HALO + LOGO --}}
                        <div class="relative inline-block">

                            {{-- HALO CUADRADO DETRÁS DEL LOGO --}}
                            <div class="absolute inset-0
                                                bg-white/35 dark:bg-white/20
                                                blur-2xl
                                                rounded-xl
                                                scale-130"></div> {{-- halo 30% más grande --}}

                            {{-- LOGO (MÁS GRANDE QUE ANTES) --}}
                            <a href="{{ route('home') }}" class="relative z-10 inline-block">
                                <img src="{{ asset('images/logoMonocromadoSinMARGEN.png') }}" alt="Gran Bretania" class="h-52 md:h-64 lg:h-72 w-auto opacity-95
                                                    drop-shadow-[0_10px_20px_rgba(0,0,0,0.55)]
                                                    dark:invert dark:brightness-0">
                            </a>

                        </div>
                    </div>



                    <div class="rounded-3xl px-6 py-3 md:px-8 md:py-4
                text-center w-auto
                bg-gradient-to-r from-azul/95 to-azul/80
                shadow-xl border border-azul/70
                whitespace-nowrap">

                        <h1 class="text-xl md:text-3xl lg:text-4xl font-semibold text-white drop-shadow-lg leading-tight">
                            Enseñanza de inglés y traducciones
                        </h1>
                    </div>


                </div>
            </div>
        </div>
    </section>




    <section class="container mx-auto px-4 pt-0 pb-14 text-center">
        <h1 class="mt-0 bg-azul text-beige2 inline-block px-8 py-4 rounded-xl dark:bg-slate-700">
            {{ __('Enseñanza de inglés y traducciones') }}
        </h1>

        {{-- BLOQUE: Clases de inglés --}}
        <section id="clases" class="bg-beige2 py-16 mt-24 dark:bg-slate-950">
            <div class="container mx-auto px-4 text-left">

                <h2 class="text-azul mb-8 dark:text-beige2">{{ __('Clases de inglés online') }}</h2>

                <div class="grid md:grid-cols-2 gap-10 items-stretch tablet-stack-768-820">

                    {{-- Texto --}}
                    <div class="h-full flex flex-col justify-between min-h-0">
                        <div class="w-full max-w-2xl mx-auto h-full flex flex-col justify-between">
                            <p class="mt-0 text-left md:text-left">
                                {!! __(
        'En :brand las clases de inglés se adaptan a ti. Con un enfoque práctico y cercano, aprenderás a comunicarte con seguridad desde el primer día. Trabajamos con una metodología flexible que combina conversación, gramática aplicada y recursos personalizados según tus objetivos.',
        ['brand' => '<span class="font-semibold">Gran Bretania</span>']
    ) !!}
                            </p>

                            {{-- Tipos de clases (resumen) --}}
                            <ul class="mt-6 grid sm:grid-cols-2 gap-4 text-base">
                                <li class="card flex items-start gap-3 bg-azul text-beige2 rounded-card p-6 dark:bg-azul">
                                    <span aria-hidden="true">🗣️</span>
                                    <div class="min-w-0">
                                        <h3 class="font-semibold mb-2">{{ __('Conversación práctica') }}</h3>
                                        <p class="opacity-80 leading-snug break-words">
                                            {{ __('Gana fluidez y naturalidad al hablar.') }}
                                        </p>
                                    </div>
                                </li>
                                <li class="card flex items-start gap-3 bg-azul text-beige2 rounded-card p-6 dark:bg-azul">
                                    <span aria-hidden="true">🎯</span>
                                    <div class="min-w-0">
                                        <h3 class="font-semibold mb-2">{{ __('Preparación de exámenes') }}</h3>
                                        <p class="opacity-80 leading-snug break-words">
                                            {{ __('Cambridge, IELTS u objetivos académicos.') }}
                                        </p>
                                    </div>
                                </li>
                                <li class="card flex items-start gap-3 bg-azul text-beige2 rounded-card p-6 dark:bg-azul">
                                    <span aria-hidden="true">💼</span>
                                    <div class="min-w-0">
                                        <h3 class="font-semibold mb-2">{{ __('Inglés profesional') }}</h3>
                                        <p class="opacity-80 leading-snug break-words">
                                            {{ __('Trabajo, presentaciones y entrevistas.') }}
                                        </p>
                                    </div>
                                </li>
                                <li class="card flex items-start gap-3 bg-azul text-beige2 rounded-card p-6 dark:bg-azul">
                                    <span aria-hidden="true">📚</span>
                                    <div class="min-w-0">
                                        <h3 class="font-semibold mb-2">{{ __('Refuerzo general') }}</h3>
                                        <p class="opacity-80 leading-snug break-words">
                                            {{ __('Comprensión, escritura y gramática.') }}
                                        </p>
                                    </div>
                                </li>
                            </ul>

                            {{-- CTA --}}
                            <div class="mt-8 flex flex-col sm:flex-row sm:justify-evenly items-center gap-4">
                                <a href="{{ route('bookings.create') }}" class="btn-primary">
                                    {{ __('Reservar clase') }}
                                </a>
                                <a href="{{ route('clases') }}" class="btn-secondary">
                                    {{ __('Saber más') }}
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Imagen / ilustración (opcional) --}}
                    <div class="order-first md:order-none h-full relative">
                        <img src="{{ asset('images/alumnoOnline.webp') }}" width="1024" height="1024"
                            alt="{{ __('Clase de inglés personalizada online') }}"
                            class="w-full h-full object-cover rounded-card shadow-sm">
                    </div>
                </div>
        </section>

        {{-- BLOQUE: Por qué elegirnos (Home) --}}
        <section class="bg-beige2 py-16 mt-6 dark:bg-slate-950">
            <div class="container mx-auto px-4 text-center">
                <h2 class="text-azul dark:text-beige2">{{ __('¿Por qué elegirnos?') }}</h2>
                <p class="mt-2 opacity-80 dark:text-slate-100">{{ __('Tres razones para empezar hoy mismo') }}</p>

                <div class="mt-10 grid md:grid-cols-3 gap-10">
                    <div>
                        <div class="mx-auto h-16 w-16 flex items-center justify-center rounded-full border-2 border-azul">
                            <span class="text-3xl" aria-hidden="true">🌐</span>
                        </div>
                        <h3 class="mt-5 font-semibold tracking-wide dark:text-beige2">{{ __('Totalmente online') }}</h3>
                        <p class="mt-2 opacity-80 dark:text-slate-100">{{ __('Clases flexibles según tu disponibilidad.') }}
                        </p>
                    </div>

                    <div>
                        <div class="mx-auto h-16 w-16 flex items-center justify-center rounded-full border-2 border-azul">
                            <span class="text-3xl" aria-hidden="true">💬</span>
                        </div>
                        <h3 class="mt-5 font-semibold tracking-wide dark:text-beige2">{{ __('Seguimiento personalizado') }}
                        </h3>
                        <p class="mt-2 opacity-80 dark:text-slate-100">
                            {{ __('Plan y feedback adaptados a tus objetivos.') }}
                        </p>
                    </div>

                    <div>
                        <div class="mx-auto h-16 w-16 flex items-center justify-center rounded-full border-2 border-azul">
                            <span class="text-3xl" aria-hidden="true">📘</span>
                        </div>
                        <h3 class="mt-5 font-semibold tracking-wide dark:text-beige2">{{ __('Material actualizado') }}</h3>
                        <p class="mt-2 opacity-80 dark:text-slate-100">
                            {{ __('Recursos actuales y prácticos para progresar.') }}
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section
            class="relative py-16 text-center transform-gpu transition-transform duration-200 hover:scale-105 bg-cover bg-center rounded-xl overflow-hidden mt-24"
            style="background-image: url('{{ asset('images/learn-english.jpg') }}')">
            <!-- Gradient overlay -->
            <div class="absolute inset-0" aria-hidden="true"
                style="background: linear-gradient(180deg, rgba(0,0,0,0.08) 0%, rgba(0,0,0,0.04) 40%, rgba(0,0,0,0.18) 100%);">
            </div>
            <div class="container mx-auto px-6 md:px-8 relative z-10 text-center">
                <div
                    class="inline-block px-6 py-6 rounded-2xl bg-gradient-to-r from-black/20 via-black/05 to-transparent backdrop-blur-sm">
                    <h2 class="text-3xl md:text-4xl font-semibold text-white mb-4">
                        {{ __('¿Tu primera vez?') }}
                    </h2>
                    <p class="text-beige2 text-lg max-w-2xl mx-auto mb-8 leading-relaxed">
                        {{ __('Empieza con una clase gratuita para conocer tu nivel y objetivos.') }}
                    </p>
                    <div class="flex flex-col sm:flex-row sm:justify-center items-center gap-4">
                        <a href="{{ route('contact.create') . '?subject=' . urlencode(__('Clase de prueba gratuita')) }}"
                            class="inline-block bg-beige2 text-azul font-semibold px-8 py-3 rounded-xl shadow-md hover:bg-rojo hover:text-white hover:shadow-lg transition">
                            {{ __('Solicitar clase de prueba') }}
                        </a>
                    </div>
                </div>
            </div>
        </section>

    </section>

    {{-- BLOQUE: Traducciones --}}
    <section id="traducciones" class="bg-beige2 py-16 mt-24 dark:bg-slate-950">
        <div class="container mx-auto px-4">

            <div class="grid md:grid-cols-2">
                <div></div>
                <div>
                    <h2 class="text-azul mb-6 max-w-2xl dark:text-beige2">{{ __('Traducciones profesionales') }}</h2>
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-10 items-stretch tablet-stack-768-820">

                {{-- Imagen--}}
                <div class="order-first md:order-none h-full relative">
                    <img src="{{ asset('images/definicion.jpg') }}" alt="{{ __('Servicio de traducciones profesionales') }}"
                        class="w-full h-full object-cover rounded-card shadow-sm">
                </div>

                {{-- Texto --}}
                <div class="h-full">
                    <div class="flex flex-col h-full">
                        <div class="flex-1 max-w-2xl min-w-0">
                            <p>
                                {!! __(
        'En :brand ofrecemos traducciones precisas, naturales y adaptadas al contexto. Cada encargo se realiza con atención al detalle y total confidencialidad, garantizando un resultado fiel al significado y tono original del texto.',
        ['brand' => '<span class="font-semibold">Gran Bretania</span>']
    ) !!}
                            </p>

                            {{-- Tipos de traducción (resumen) --}}
                            <ul class="mt-6 grid sm:grid-cols-2 gap-4 text-base">
                                <li class="card flex items-start gap-3 bg-beige dark:bg-slate-900">
                                    <span aria-hidden="true">⚖️</span>
                                    <div class="min-w-0">
                                        <h3 class="font-semibold mb-2">{{ __('Jurídica') }}</h3>
                                        <p class="opacity-80 leading-snug break-words">
                                            {{ __('Contratos, documentos legales y certificados.') }}
                                        </p>
                                    </div>
                                </li>
                                <li class="card flex items-start gap-3 bg-beige dark:bg-slate-900">
                                    <span aria-hidden="true">💊</span>
                                    <div class="min-w-0">
                                        <h3 class="font-semibold mb-2">{{ __('Médica') }}</h3>
                                        <p class="opacity-80 leading-snug break-words">
                                            {{ __('Informes clínicos y documentación sanitaria.') }}
                                        </p>
                                    </div>
                                </li>
                                <li class="card flex items-start gap-3 bg-beige dark:bg-slate-900">
                                    <span aria-hidden="true">🎓</span>
                                    <div class="min-w-0">
                                        <h3 class="font-semibold mb-2">{{ __('Académica') }}</h3>
                                        <p class="opacity-80 leading-snug break-words">
                                            {{ __('Artículos, proyectos y trabajos de investigación.') }}
                                        </p>
                                    </div>
                                </li>
                                <li class="card flex items-start gap-3 bg-beige dark:bg-slate-900">
                                    <span aria-hidden="true">🎬</span>
                                    <div class="min-w-0">
                                        <h3 class="font-semibold mb-2">{{ __('Audiovisual') }}</h3>
                                        <p class="opacity-80 leading-snug break-words">
                                            {{ __('Subtitulación y guiones adaptados al público objetivo.') }}
                                        </p>
                                    </div>
                                </li>
                            </ul>

                            {{-- CTA (alineada con la columna de texto) --}}
                            <div class="mt-8">
                                <div class="flex flex-col sm:flex-row sm:justify-evenly items-center gap-3">
                                    <a href="{{ route('translation.create') }}"
                                        class="btn-primary w-full sm:w-auto text-center">
                                        {{ __('Solicitar traducción') }}
                                    </a>
                                    <a href="{{ route('traducciones') }}"
                                        class="btn-secondary w-full sm:w-auto text-center">
                                        {{ __('Ver más información') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="empresas" class="relative py-20 text-white overflow-hidden mt-24">
        {{-- Imagen de fondo --}}
        <div class="absolute inset-0">
            <img src="{{ asset('images/empresarios.jpg') }}" alt="{{ __('Solución integral en inglés para empresas') }}"
                class="w-full h-full object-cover brightness-90">
        </div>

        {{-- Capa azul translúcida --}}
        <div class="absolute inset-0 bg-gradient-to-r from-azul/60 via-azul/40 to-transparent"></div>

        {{-- Contenido principal --}}
        <div class="relative container mx-auto px-4">
            <div class="text-left max-w-4xl">
                <h2 class="text-3xl md:text-4xl font-semibold mb-4">
                    {{ __('Solución integral para tu empresa') }}
                </h2>
                <p class="mt-3 text-white/90 leading-relaxed">
                    {{ __('Traducciones especializadas, interpretación en tiempo real y formación en inglés profesional para equipos.') }}<br>
                    {{ __('Un único proveedor, procesos ágiles y resultados medibles.') }}
                </p>
            </div>

            {{-- Servicios clave --}}
            <div class="mt-10 grid md:grid-cols-3 gap-6">
                <article class="bg-black/40 hover:bg-black/50 rounded-xl p-6 backdrop-blur-sm transition">
                    <h3 class="font-semibold text-xl text-white">
                        {{ __('Traducción especializada') }}
                    </h3>
                    <p class="mt-2 text-white/90 leading-snug">
                        {{ __('Jurídica, médica, académica y audiovisual. Terminología precisa, control de calidad y entregas puntuales.') }}
                    </p>
                    <ul class="mt-3 text-sm text-white/80 list-disc ml-5">
                        <li>{{ __('Memorias y glosarios de empresa') }}</li>
                        <li>{{ __('Revisión y maquetación') }}</li>
                    </ul>
                    <a href="{{ route('traducciones') }}" class="mt-4 inline-block btn-secondary">
                        {{ __('Ver traducciones →') }}
                    </a>
                </article>

                <article class="bg-black/40 hover:bg-black/50 rounded-xl p-6 backdrop-blur-sm transition">
                    <h3 class="font-semibold text-xl text-white">
                        {{ __('Interpretación') }}
                    </h3>
                    <p class="mt-2 text-white/90 leading-snug">
                        {{ __('Consecutiva o simultánea para reuniones, webinars y eventos online. Comunicación fluida entre equipos y clientes.') }}
                    </p>
                    <ul class="mt-3 text-sm text-white/80 list-disc ml-5">
                        <li>{{ __('Briefing previo y guía terminológica') }}</li>
                        <li>{{ __('Soporte técnico de sala virtual') }}</li>
                    </ul>
                    <a href="{{ route('contact.create') . '?subject=' . urlencode(__('Solicitar interprete')) }}"
                        class="mt-4 inline-block btn-secondary">
                        {{ __('Solicitar intérprete →') }}
                    </a>
                </article>

                <article class="bg-black/40 hover:bg-black/50 rounded-xl p-6 backdrop-blur-sm transition">
                    <h3 class="font-semibold text-xl text-white">
                        {{ __('Formación in-company') }}
                    </h3>
                    <p class="mt-2 text-white/90 leading-snug">
                        {{ __('Inglés profesional para equipos: reuniones, presentaciones, email y entrevistas. Programas a medida.') }}
                    </p>
                    <ul class="mt-3 text-sm text-white/80 list-disc ml-5">
                        <li>{{ __('Diagnóstico de nivel y objetivos') }}</li>
                        <li>{{ __('Material exclusivo y prácticos reales') }}</li>
                    </ul>
                    <a href="{{ route('clases') }}" class="mt-4 inline-block btn-secondary">
                        {{ __('Ver formación →') }}
                    </a>
                </article>
            </div>
        </div>
    </section>

    <section class="bg-beige2 py-16 mt-24 dark:bg-slate-950">
        <div class="container mx-auto px-4 grid md:grid-cols-2 gap-10 items-center">
            <img src="{{ asset('images/profe.png') }}" alt="{{ __('Tania Morais Villar') }}"
                class="rounded-xl shadow-md object-cover h-full max-h-96 object-top h-80 w-full">
            <div>
                <h2 class="text-azul text-3xl font-semibold mb-4 dark:text-beige2">{{ __('Sobre mí') }}</h2>
                <p class="text-gray-700 leading-relaxed dark:text-slate-100">
                    {!! __(
        'Soy Tania, profesora de inglés y traductora profesional. En :brand combino años de experiencia docente con una atención personalizada, adaptando cada clase o proyecto a las necesidades de mis alumnos y clientes.',
        ['brand' => '<strong>Gran Bretania</strong>']
    ) !!}
                </p>
                <a href="{{ route('sobremi') }}" class="btn-secondary mt-6 inline-block">
                    {{ __('Conóceme mejor') }}
                </a>
            </div>
        </div>
    </section>

    <section id="opiniones" class="bg-beige py-8 mt-12 dark:bg-slate-950">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-azul mb-6 text-2xl dark:text-beige2">
                {{ __('Opiniones de nuestros alumnos y clientes') }}
            </h2>

            <div class="relative max-w-4xl mx-auto">
                {{-- Carrusel --}}
                <div class="overflow-hidden">
                    <div id="opinionesTrack" class="flex transition-transform duration-500 ease-out">

                        {{-- SLIDE 1: opiniones 1–3 --}}
                        <div class="min-w-full px-2" data-opinion-slide>
                            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">

                                {{-- Opinión 1 --}}
                                <div class="card bg-white dark:bg-slate-900">
                                    <div class="flex flex-col items-center text-center p-4">
                                        <p class="italic text-sm">
                                            {{ __('Las clases con Tania me ayudaron a ganar confianza hablando en inglés. El ambiente es cercano y muy profesional.') }}
                                        </p>
                                        <p class="mt-3 font-semibold text-azul text-sm dark:text-beige2">María L.</p>
                                        <p class="text-xs opacity-80">{{ __('Estudiante de conversación') }}</p>
                                    </div>
                                </div>

                                {{-- Opinión 2 --}}
                                <div class="card bg-white dark:bg-slate-900">
                                    <div class="flex flex-col items-center text-center p-4">
                                        <p class="italic text-base">
                                            {{ __('Encargué una traducción médica y quedé encantada con la precisión y rapidez. Muy recomendable.') }}
                                        </p>
                                        <p class="mt-4 font-semibold text-azul dark:text-beige2">Laura G.</p>
                                        <p class="text-sm opacity-80">{{ __('Cliente de traducción') }}</p>
                                    </div>
                                </div>

                                {{-- Opinión 3 --}}
                                <div class="card bg-white dark:bg-slate-900">
                                    <div class="flex flex-col items-center text-center p-4">
                                        <p class="italic text-base">
                                            {{ __('Las clases online son dinámicas y se adaptan a mis horarios. Aprender inglés así da gusto.') }}
                                        </p>
                                        <p class="mt-4 font-semibold text-azul dark:text-beige2">David R.</p>
                                        <p class="text-sm opacity-80">{{ __('Alumno de inglés profesional') }}</p>
                                    </div>
                                </div>

                            </div>
                        </div>

                        {{-- SLIDE 2: opiniones 4–6 --}}
                        <div class="min-w-full px-2" data-opinion-slide>
                            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">

                                {{-- Opinión 4 --}}
                                <div class="card bg-white dark:bg-slate-900">
                                    <div class="flex flex-col items-center text-center p-4">
                                        <p class="italic text-sm">
                                            {{ __('Preparé con Tania una presentación importante en inglés y salió muchísimo mejor de lo que esperaba. Me ayudó con el vocabulario y con la seguridad al hablar.') }}
                                        </p>
                                        <p class="mt-3 font-semibold text-azul text-sm dark:text-beige2">Ana P.</p>
                                        <p class="text-xs opacity-80">{{ __('Inglés profesional') }}</p>
                                    </div>
                                </div>

                                {{-- Opinión 5 --}}
                                <div class="card bg-white dark:bg-slate-900">
                                    <div class="flex flex-col items-center text-center p-4">
                                        <p class="italic text-base">
                                            {{ __('Las clases son amenas, muy personalizadas y siempre me voy con la sensación de haber aprendido algo útil. Totalmente recomendable.') }}
                                        </p>
                                        <p class="mt-4 font-semibold text-azul dark:text-beige2">Jorge M.</p>
                                        <p class="text-sm opacity-80">{{ __('Alumno de nivel B1') }}</p>
                                    </div>
                                </div>

                                {{-- Opinión 6 --}}
                                <div class="card bg-white dark:bg-slate-900">
                                    <div class="flex flex-col items-center text-center p-4">
                                        <p class="italic text-base">
                                            {{ __('Necesitábamos una traducción para un proyecto internacional y Tania nos entregó un trabajo impecable. Comunicación rápida y resultados excelentes.') }}
                                        </p>
                                        <p class="mt-4 font-semibold text-azul dark:text-beige2">Estudio Creativo Nexo</p>
                                        <p class="text-sm opacity-80">{{ __('Cliente de traducción empresarial') }}</p>
                                    </div>
                                </div>

                            </div>
                        </div>

                        {{-- SLIDE 3: opiniones 7–9 --}}
                        <div class="min-w-full px-2" data-opinion-slide>
                            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">

                                {{-- Opinión 7 --}}
                                <div class="card bg-white dark:bg-slate-900">
                                    <div class="flex flex-col items-center text-center p-4">
                                        <p class="italic text-sm">
                                            {{ __('Gracias a las clases pude presentarme al examen de Cambridge con mucha más tranquilidad. Trabajamos justo lo que necesitaba.') }}
                                        </p>
                                        <p class="mt-3 font-semibold text-azul text-sm dark:text-beige2">Clara S.</p>
                                        <p class="text-xs opacity-80">{{ __('Preparación Cambridge') }}</p>
                                    </div>
                                </div>

                                {{-- Opinión 8 --}}
                                <div class="card bg-white dark:bg-slate-900">
                                    <div class="flex flex-col items-center text-center p-4">
                                        <p class="italic text-base">
                                            {{ __('Organizamos clases para el equipo y hemos notado mejora real en las reuniones con clientes internacionales.') }}
                                        </p>
                                        <p class="mt-4 font-semibold text-azul dark:text-beige2">Dept. Ventas</p>
                                        <p class="text-sm opacity-80">{{ __('Formación in-company') }}</p>
                                    </div>
                                </div>

                                {{-- Opinión 9 --}}
                                <div class="card bg-white dark:bg-slate-900">
                                    <div class="flex flex-col items-center text-center p-4">
                                        <p class="italic text-base">
                                            {{ __('Contamos con Tania para una sesión de interpretación online y todo fluyó sin problemas, tanto a nivel técnico como de idioma.') }}
                                        </p>
                                        <p class="mt-4 font-semibold text-azul dark:text-beige2">Marcos V.</p>
                                        <p class="text-sm opacity-80">{{ __('Servicio de interpretación') }}</p>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>

                {{-- Puntos --}}
                <div class="flex justify-center gap-2 mt-4">
                    <button type="button" class="w-2.5 h-2.5 rounded-full bg-azul" data-opinion-dot="0"
                        aria-label="{{ __('Opiniones 1 a 3') }}"></button>

                    <button type="button" class="w-2 h-2 rounded-full bg-beige2" data-opinion-dot="1"
                        aria-label="{{ __('Opiniones 4 a 6') }}"></button>

                    <button type="button" class="w-2 h-2 rounded-full bg-beige2" data-opinion-dot="2"
                        aria-label="{{ __('Opiniones 7 a 9') }}"></button>
                </div>

            </div>
        </div>
    </section>

@endsection
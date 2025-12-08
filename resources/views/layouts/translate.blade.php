{{--
    Layout: translate.blade.php
    página de servicios de traducción (idiomas, tipos y tarifas).
   
--}}

@extends('layouts.site')

@section('title', 'Traducciones · Gran Bretania')

@section('content')
    <section id="traduccion" class="py-16 bg-beige2 dark:bg-slate-950">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Intro --}}
            <div class="max-w-3xl mb-10">
                <h2 class="text-azul text-3xl md:text-4xl font-semibold dark:text-beige2">
                    Traducción e interpretación profesional
                </h2>
                <p class="mt-3 text-gray-700 dark:text-slate-100">
                    Traducciones especializadas con rigor terminológico, adaptación al contexto y total confidencialidad.
                    Trabajamos con <strong>español, inglés y francés</strong>, con las siguientes combinaciones disponibles.
                </p>
            </div>

            {{-- Idiomas y envío de documentos --}}
            <div class="bg-azul rounded-2xl shadow p-8 mb-14">
                <h3 class="text-white text-xl font-semibold mb-4">Idiomas disponibles</h3>

                <ul class="grid sm:grid-cols-3 gap-4 text-sm text-gray-700 mb-6 dark:text-slate-100">

                    {{-- Español ↔ Inglés --}}
                    <li class="p-4 border rounded-lg bg-beige dark:bg-slate-900 dark:border-slate-700">
                        <div class="flex items-center gap-2 mb-1">
                            <img src="/images/es.svg" alt="ES" class="w-5 h-5 rounded-full border">
                            <img src="/images/gb.svg" alt="EN" class="w-5 h-5 rounded-full border">
                        </div>
                        <strong>ES ⇄ EN</strong><br>Español ↔ Inglés
                    </li>

                    {{-- Francés → Español --}}
                    <li class="p-4 border rounded-lg bg-beige dark:bg-slate-900 dark:border-slate-700">
                        <div class="flex items-center gap-2 mb-1">
                            <img src="/images/fr.svg" alt="FR" class="w-5 h-5 rounded-full border">
                            <img src="/images/es.svg" alt="ES" class="w-5 h-5 rounded-full border">
                        </div>
                        <strong>FR → ES</strong><br>Francés → Español
                    </li>

                    {{-- Francés → Inglés --}}
                    <li class="p-4 border rounded-lg bg-beige dark:bg-slate-900 dark:border-slate-700">
                        <div class="flex items-center gap-2 mb-1">
                            <img src="/images/fr.svg" alt="FR" class="w-5 h-5 rounded-full border">
                            <img src="/images/gb.svg" alt="EN" class="w-5 h-5 rounded-full border">
                        </div>
                        <strong>FR → EN</strong><br>Francés → Inglés
                    </li>

                </ul>

                <p class="text-white text-sm">
                    Los documentos se envían directamente a través del
                    <strong>formulario de contacto</strong> (PDF, JPG o DOC).Se almacenan de forma segura siguiendo la
                    <a href="{{ route('privacy') }}" class="text-beige2 underline hover:text-rojo">política de privacidad</a>.
                </p>
            </div>

            {{-- Tipos de traducción --}}
            <section class="py-12">
                <div class="container mx-auto px-4">
                    <h3 class="text-azul text-2xl md:text-3xl font-semibold mb-6 dark:text-beige2">
                        Tipos de traducción
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

                        {{-- TARJETA 1: Jurídica --}}
                        <article class="bg-azul rounded-2xl shadow overflow-hidden text-white group transform transition-transform duration-300 hover:scale-105 hover:z-10">
                            <div class="relative h-44 group ">
                                <img src="{{ asset('images/traduccion-jurada.jpg') }}" alt="Traducción jurídica"
                                    class="w-full h-full object-cover ">

                                
                                <div
                                    class="absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-black/80 via-black/40 to-transparent p-4 flex items-end">
                                    <h4 class="text-white font-semibold text-lg">Traducción jurídica</h4>
                                </div>
                            </div>

                            <div class="p-4">
                                <p class="text-700 text-sm leading-relaxed">
                                    Contratos, certificados, escrituras, poderes notariales y documentación administrativa.
                                </p>
                            </div>
                        </article>

                        {{-- TARJETA 2: Médica --}}
                        <article class="bg-azul rounded-2xl shadow overflow-hidden text-white group transform transition-transform duration-300 hover:scale-105 hover:z-10">
                            <div class="relative h-44 group">
                                <img src="{{ asset('images/traduccion-medica.jpg') }}" alt="Traducción médica"
                                    class="w-full h-full object-cover">

                                <div
                                    class="absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-black/80 via-black/40 to-transparent p-4 flex items-end">
                                    <h4 class="text-white font-semibold text-lg">Traducción médica</h4>
                                </div>
                            </div>

                            <div class="p-4">
                                <p class="text-700 text-sm leading-relaxed">
                                    Informes clínicos, historiales médicos, resultados de pruebas y documentación sanitaria.
                                </p>
                            </div>
                        </article>

                        {{-- TARJETA 3: Académica --}}
                        <article class="bg-azul rounded-2xl shadow overflow-hidden text-white group transform transition-transform duration-300 hover:scale-105 hover:z-10">
                            <div class="relative h-44 group">
                                <img src="{{ asset('images/traduccion-academica.jpg') }}" alt="Traducción académica"
                                    class="w-full h-full object-cover">

                                <div
                                    class="absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-black/80 via-black/40 to-transparent p-4 flex items-end">
                                    <h4 class="text-white font-semibold text-lg">Traducción académica</h4>
                                </div>
                            </div>

                            <div class="p-4">
                                <p class="text-700 text-sm leading-relaxed">
                                    Tesis, TFG/TFM, artículos científicos, expedientes y documentación universitaria.
                                </p>
                            </div>
                        </article>

                        {{-- TARJETA 4: Audiovisual --}}
                        <article class="bg-azul rounded-2xl shadow overflow-hidden text-white group transform transition-transform duration-300 hover:scale-105 hover:z-10">
                            <div class="relative h-44 group">
                                <img src="{{ asset('images/traduccion-audiovisual.jpg') }}" alt="Traducción audiovisual"
                                    class="w-full h-full object-cover">

                                <div
                                    class="absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-black/80 via-black/40 to-transparent p-4 flex items-end">
                                    <h4 class="text-white font-semibold text-lg">Traducción audiovisual</h4>
                                </div>
                            </div>

                            <div class="p-4">
                                <p class="text-700 text-sm leading-relaxed">
                                    Subtitulación, transcripción, guiones y localización de vídeos y material multimedia.
                                </p>
                            </div>
                        </article>

                    </div>
                </div>
            </section>

            {{-- Tarifas --}}
            <div class="max-w-3xl mx-auto text-center">
                <h3 class="text-azul text-2xl font-semibold mb-4 dark:text-beige2">Tarifas y condiciones</h3>
                <p class="text-gray-700 mb-6 dark:text-slate-100">
                    Las traducciones se calculan <strong>por palabra del original</strong> y varían según dificultad y
                    especialidad.
                </p>

                <div class="inline-block bg-beige rounded-2xl shadow px-8 py-6 dark:bg-slate-900">
                    <p class="text-2xl font-semibold text-azul dark:text-beige2">Desde 0,08 € / palabra</p>
                    <p class="text-gray-600 text-sm mt-1 dark:text-slate-200">Presupuesto en menos de 48 h</p>
                </div>

                <div class="mt-8 flex flex-wrap justify-center gap-4">
                    <a href="{{ route('translation.create') }}" class="btn-three">Solicitar presupuesto</a>
                </div>
            </div>

        </div>
    </section>

    <section id="proceso-traducciones" class="py-16 bg-beige2 dark:bg-slate-950">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 grid lg:grid-cols-2 gap-6 items-start">

            {{-- Proceso--}}
            <div>
                <h3 class="text-azul text-3xl md:text-4xl font-semibold mb-6 dark:text-beige2">
                    Proceso de trabajo
                </h3>

                <p class="text-gray-700 mb-10 max-w-md dark:text-slate-100">
                    Un proceso claro y seguro para que recibas tu traducción en plazo,
                    con confidencialidad y seguimiento en cada etapa.
                </p>

                {{-- Timeline --}}
                <ol class="relative border-s border-gray-300 space-y-10 dark:border-slate-700">

                    {{-- Paso 1: Registro --}}
                    <li class="ms-6">
                        <span
                            class="absolute -start-3 w-8 h-8 flex items-center justify-center 
                                                                                       rounded-full bg-azul text-white font-semibold text-sm">
                            1
                        </span>
                        <h4 class="text-azul font-semibold text-lg dark:text-beige2">Regístrate</h4>
                        <p class="text-gray-700 text-sm mt-1 dark:text-slate-100">
                            Crea tu cuenta para poder enviar documentos, ver el estado de tus encargos y gestionar tus
                            datos.
                        </p>
                        <a href="{{ route('register') }}"
                            class="inline-block mt-2 text-azul underline hover:text-rojo text-sm dark:text-beige2">Crear cuenta</a>
                    </li>

                    {{-- Paso 2: Envío de documentos --}}
                    <li class="ms-6">
                        <span
                            class="absolute -start-3 w-8 h-8 flex items-center justify-center 
                                                                                       rounded-full bg-azul text-white font-semibold text-sm">
                            2
                        </span>
                        <h4 class="text-azul font-semibold text-lg dark:text-beige2">Envío de documentos</h4>
                        <p class="text-gray-700 text-sm mt-1 dark:text-slate-100">
                            Sube tus documentos escaneados (PDF, JPG, DOC) directamente desde el formulario.
                        </p>
                    </li>

                    {{-- Paso 3: Selección de idiomas --}}
                    <li class="ms-6">
                        <span
                            class="absolute -start-3 w-8 h-8 flex items-center justify-center
                                                                                       rounded-full bg-azul text-white font-semibold text-sm">
                            3
                        </span>
                        <h4 class="text-azul font-semibold text-lg dark:text-beige2">Selecciona idioma origen y destino</h4>
                        <p class="text-gray-700 text-sm mt-1 dark:text-slate-100">
                            Trabajamos con <strong>español, inglés y francés</strong> en ambas direcciones.
                        </p>
                    </li>

                    {{-- Paso 4: Presupuesto --}}
                    <li class="ms-6">
                        <span
                            class="absolute -start-3 w-8 h-8 flex items-center justify-center 
                                                                                       rounded-full bg-azul text-white font-semibold text-sm">
                            4
                        </span>
                        <h4 class="text-azul font-semibold text-lg dark:text-beige2">Presupuesto</h4>
                        <p class="text-gray-700 text-sm mt-1 dark:text-slate-100">
                            Te enviaremos un presupuesto personalizado según cantidad de palabras, especialidad y urgencia.
                        </p>
                    </li>

                    {{-- Paso 5: Traducción --}}
                    <li class="ms-6">
                        <span
                            class="absolute -start-3 w-8 h-8 flex items-center justify-center 
                                                                                       rounded-full bg-azul text-white font-semibold text-sm">
                            5
                        </span>
                        <h4 class="text-azul font-semibold text-lg dark:text-beige2">Traducción en plazo</h4>
                        <p class="text-gray-700 text-sm mt-1 dark:text-slate-100">
                            Traducimos con rigor terminológico, revisión doble y entrega en la fecha acordada.
                        </p>
                    </li>

                    {{-- Paso 6: Entrega y privacidad --}}
                    <li class="ms-6">
                        <span
                            class="absolute -start-3 w-8 h-8 flex items-center justify-center 
                                                                                       rounded-full bg-azul text-white font-semibold text-sm">
                            6
                        </span>
                        <h4 class="text-azul font-semibold text-lg dark:text-beige2">Entrega final</h4>
                        <p class="text-gray-700 text-sm mt-1 dark:text-slate-100">
                            Recibe la traducción por email o en físico si lo necesitas.
                            Los documentos se almacenan siguiendo la
                            <a href="{{ route('privacy') }}" class="text-azul underline hover:text-rojo dark:text-beige2">política de
                                privacidad</a>.
                        </p>
                    </li>

                </ol>
            </div>

            {{-- Imagen derecha --}}
            <div class="flex justify-center lg:justify-end">
                <img src="{{ asset('images/pc.png') }}" alt="Proceso de traducción"
                    class="w-full max-w-full sm:max-w-md mx-auto rounded-2xl shadow-lg object-cover dark:shadow-slate-900">
            </div>

        </div>
    </section>

    <section id="interpretacion" class="py-16 bg-beige2 dark:bg-slate-950">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 grid lg:grid-cols-2 gap-10 items-stretch">
            {{-- Imagen para horizontal --}}
            <div class="w-full h-full">
                <img src="images/interpretacion.jpg" alt="Interpretación online"
                    class="rounded-2xl shadow-lg w-full h-full object-cover object-center dark:shadow-slate-900" />
            </div>
            {{-- Texto --}}
            <div>
                <h2 class="text-azul text-3xl md:text-4xl font-semibold dark:text-beige2">Interpretación online</h2>
                <p class="mt-4 text-gray-700 dark:text-slate-100">
                    Interpretación <strong>solo bidireccional inglés ↔ español</strong>, ideal para reuniones, entrevistas,
                    webinars y sesiones internacionales.
                </p>
                <p class="mt-4 text-gray-700 dark:text-slate-100">
                    Nos conectamos a través de <strong>Google Meet</strong>, sin desplazamientos y con pruebas
                    técnicas previas.
                </p>

                <ul class="mt-6 space-y-2 text-gray-700 text-sm list-disc ml-5 dark:text-slate-100">
                    <li>Interpretación consecutiva o simultánea online</li>
                    <li>Briefing previo y guía terminológica</li>
                    <li>Soporte técnico antes de la reunión</li>
                    <li>Resumen opcional después del evento</li>
                </ul>

                <div class="mt-8 flex flex-wrap gap-4">
                    <a href="{{ route('contact.create', ['subject' => 'Solicitar intérprete']) }}"
                        class="btn-primary">Solicitar intérprete</a>
                    <a href="{{ route('contact.create', ['subject' => 'Presupuesto interpretación']) }}"
                        class="btn-three text-white">Pedir presupuesto</a>
                </div>
            </div>
        </div>
    </section>

@endsection

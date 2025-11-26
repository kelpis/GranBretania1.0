{{--
    Layout: aboutme.blade.php
    Propósito: página "Sobre mí" con biografía, valores y CTA.
    Notas: usa el layout `layouts.site`; mantener contenido estático y accesible.
--}}

@extends('layouts.site')

@section('title', 'Sobre mí · Gran Bretania')

@section('header')


@section('content')
    <section class="bg-beige2 py-12 dark:bg-slate-950">
        <div class="container mx-auto px-4 max-w-5xl">

            {{-- HERO SOBRE MÍ --}}
            <div class="grid md:grid-cols-2 gap-8 items-start mb-12 md:mb-16">
                <div class="flex flex-col md:h-[36rem]">
                    <h1 class="text-azul text-3xl md:text-4xl font-semibold mb-6 md:mb-8 dark:text-beige2">
                        Hola, soy Tania — tu profe de inglés y traductora en <span class="text-rojo">Gran Bretania</span>
                    </h1>

                    <div class="flex flex-col gap-4">
                        <p class="text-gray-700 leading-relaxed dark:text-slate-100">
                            Ayudo a personas y empresas a comunicarse en inglés con seguridad, claridad y un punto
                            de humor británico. Combino mi experiencia como docente y traductora para ofrecer clases
                            prácticas y traducciones cuidando cada matiz.
                        </p>
                        <p class="text-gray-700 leading-relaxed dark:text-slate-100">
                            Si buscas clases cercanas, enfocadas en tu día a día, o necesitas una traducción profesional,
                            estás en el sitio adecuado.
                        </p>
                    </div>

                    {{-- CTA: LinkedIn --}}
                    <div class="flex-1 flex items-center justify-center mt-4">
                        <a href="https://www.linkedin.com/in/tania" target="_blank" rel="noopener noreferrer"
                           class="inline-flex items-center px-6 py-3 md:px-6 md:py-3 rounded-full bg-rojo text-white text-base md:text-lg hover:bg-beige hover:text-azul transition shadow-sm">
                            Visita mi LinkedIn
                        </a>
                    </div>
                </div>

                {{-- FOTO / ILUSTRACIÓN --}}
                <div class="flex justify-center">
                    <div class="rounded-2xl overflow-hidden bg-beige2 w-full md:max-w-xl dark:bg-slate-900">
                        
                            <img src="/images/tania.jpg" alt="Tania, profesora de inglés y traductora en Gran Bretania"
                                class="w-full h-[36rem] object-cover object-center" style="object-position: center 25%;">
                    </div>
                </div>
            </div>

            {{-- QUIÉN SOY --}}
            <div class="bg-beige rounded-2xl shadow p-6 md:p-8 mb-10 md:mb-16 dark:bg-slate-900">
                <h2 class="text-azul text-2xl font-semibold mb-3 dark:text-beige2">Quién soy</h2>
                <p class="text-gray-700 leading-relaxed mb-3 dark:text-slate-100">
                    Soy Tania, profesora de inglés y traductora. Llevo varios años acompañando a estudiantes de distintos
                    niveles —desde quienes empiezan desde cero hasta quienes necesitan el inglés para su trabajo— y
                    colaborando con pequeñas empresas que quieren dar el salto a materiales bilingües.
                </p>
                <p class="text-gray-700 leading-relaxed mb-3 dark:text-slate-100">
                    Me gusta que las clases sean prácticas, amables y realistas: trabajamos con ejemplos cercanos a tu
                    vida diaria, tus intereses y tus objetivos. Nada de libros eternos sin contexto; la idea es que el
                    inglés te sirva para algo desde el primer día.
                </p>
                <p class="text-gray-700 leading-relaxed dark:text-slate-100">
                    En traducción, mi objetivo es mantener tu voz y tu intención en el otro idioma, respetando el tono,
                    el registro y el contexto. No se trata solo de “pasar palabras”, sino de comunicar lo mismo a otra
                    audiencia.
                </p>
            </div>

            {{-- CÓMO SON LAS CLASES --}}
            <div class="grid md:grid-cols-2 gap-8 mb-10 md:mb-12">
                <div class="bg-azul text-beige2 rounded-2xl shadow p-6 md:p-7">
                    <h3 class="text-xl font-semibold mb-3">Clases de inglés a tu ritmo</h3>
                    <ul class="list-disc pl-5 space-y-2 text-sm md:text-base">
                        <li>Clases individuales online, centradas en tus objetivos reales.</li>
                        <li>Refuerzo de gramática sin agobios, aplicada a situaciones concretas.</li>
                        <li>Práctica de conversación con correcciones suaves y explicadas.</li>
                        <li>Material complementario adaptado a tu nivel y a tu tiempo disponible.</li>
                    </ul>
                    <p class="text-sm text-white/80 mt-4">
                        Ya sea para viajar, mejorar en el trabajo o aprobar un examen, diseñamos el plan juntos.
                    </p>
                </div>

                <div class="bg-white rounded-2xl shadow p-6 md:p-7 dark:bg-slate-900">
                    <h3 class="text-azul text-xl font-semibold mb-3 dark:text-beige2">Traducción y revisión de textos</h3>
                    <ul class="list-disc pl-5 space-y-2 text-sm md:text-base text-gray-700 dark:text-slate-100">
                        <li>Traducción EN–ES–FR de documentos generales y de ámbito profesional.</li>
                        <li>Revisión y corrección de estilo para textos ya traducidos.</li>
                        <li>Cuidado especial de la terminología y del tono según el público.</li>
                        <li>Comunicación clara durante el proceso para resolver dudas.</li>
                    </ul>
                    <p class="text-sm text-gray-600 mt-4 dark:text-slate-300">
                        Cada texto se trata de forma confidencial y con la máxima atención al detalle.
                    </p>
                </div>
            </div>

            {{-- VALORES / MANERA DE TRABAJAR --}}
            <div class="mb-12 md:mb-16">
                <h2 class="text-azul text-2xl font-semibold mb-4 dark:text-beige2">Mi manera de trabajar</h2>
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <article
                        class="bg-white rounded-2xl shadow p-5 border border-beige dark:bg-slate-900 dark:border-slate-700">
                        <h3 class="text-azul font-semibold mb-2 text-sm uppercase tracking-wide dark:text-beige2">Cercanía
                        </h3>
                        <p class="text-gray-700 text-sm dark:text-slate-100">
                            Clases y traducciones con trato directo, honesto y cercano. Puedes preguntar todo lo que
                            necesites, sin miedo a “molestar”.
                        </p>
                    </article>

                    <article
                        class="bg-white rounded-2xl shadow p-5 border border-beige dark:bg-slate-900 dark:border-slate-700">
                        <h3 class="text-azul font-semibold mb-2 text-sm uppercase tracking-wide dark:text-beige2">Claridad
                        </h3>
                        <p class="text-gray-700 text-sm dark:text-slate-100">
                            Explicaciones en un lenguaje sencillo, sin tecnicismos innecesarios. Busco que entiendas el
                            porqué de cada corrección.
                        </p>
                    </article>

                    <article
                        class="bg-white rounded-2xl shadow p-5 border border-beige dark:bg-slate-900 dark:border-slate-700">
                        <h3 class="text-azul font-semibold mb-2 text-sm uppercase tracking-wide dark:text-beige2">Adaptación
                        </h3>
                        <p class="text-gray-700 text-sm dark:text-slate-100">
                            No hay dos estudiantes iguales ni dos textos iguales: ajusto el ritmo, el contenido y el
                            enfoque a lo que tú necesitas.
                        </p>
                    </article>

                    <article
                        class="bg-white rounded-2xl shadow p-5 border border-beige dark:bg-slate-900 dark:border-slate-700">
                        <h3 class="text-azul font-semibold mb-2 text-sm uppercase tracking-wide dark:text-beige2">Rigor</h3>
                        <p class="text-gray-700 text-sm dark:text-slate-100">
                            Respeto por la gramática, la terminología y los matices culturales en ambas direcciones del
                            idioma.
                        </p>
                    </article>

                    <article
                        class="bg-white rounded-2xl shadow p-5 border border-beige dark:bg-slate-900 dark:border-slate-700">
                        <h3 class="text-azul font-semibold mb-2 text-sm uppercase tracking-wide dark:text-beige2">
                            Confidencialidad</h3>
                        <p class="text-gray-700 text-sm dark:text-slate-100">
                            Trato toda la información y documentación con la máxima discreción y cuidado.
                        </p>
                    </article>

                    <article
                        class="bg-white rounded-2xl shadow p-5 border border-beige dark:bg-slate-900 dark:border-slate-700">
                        <h3 class="text-azul font-semibold mb-2 text-sm uppercase tracking-wide dark:text-beige2">
                            Acompañamiento</h3>
                        <p class="text-gray-700 text-sm dark:text-slate-100">
                            No se trata solo de “corregir”, sino de acompañarte para que ganes seguridad usando el idioma.
                        </p>
                    </article>
                </div>
            </div>

            {{-- CTA FINAL --}}
            <div
                class="bg-azul text-beige2 rounded-2xl shadow px-6 py-8 md:px-10 text-center md:text-left flex flex-col md:flex-row items-center justify-between gap-4 mt-0 md:mt-8">
                <div>
                    <h2 class="text-xl font-semibold mb-1">¿Hablamos?</h2>
                    <p class="text-sm md:text-base text-beige2/90">
                        Si tienes dudas sobre las clases o necesitas un presupuesto de traducción,
                        puedes contarme tu caso sin compromiso.
                    </p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('contact.create') }}"
                        class="inline-flex items-center px-5 py-2 rounded-full bg-beige text-azul font-medium text-sm hover:bg-rojo transition dark:bg-slate-900 dark:text-beige2">
                        Ir al formulario de contacto
                    </a>
                    <a href="{{ route('clases') }}"
                        class="inline-flex items-center px-5 py-2 rounded-full bg-rojo text-white text-sm hover:bg-beige hover:text-azul transition">
                        Ver opciones de clases
                    </a>
                </div>
            </div>

        </div>
    </section>
@endsection
{{--
    Layout: faq.blade.php
    Propósito: página de preguntas frecuentes (FAQ).
    Notas: usar listas <details> para accesibilidad y mantener contenido actualizado.
--}}

@extends('layouts.site')

@section('title', 'FAQ · Gran Bretania')

@section('content')

    <section id="faq" class="bg-beige2 py-16 dark:bg-slate-950">

        <div class="container mx-auto px-4 max-w-3xl">
            <h2 class="text-azul text-3xl font-semibold mb-10 text-center dark:text-beige2">
                Preguntas frecuentes
            </h2>

            <div class="space-y-4">

                <details class="bg-white rounded-lg shadow p-4 dark:bg-slate-900">
                    <summary class="font-semibold text-azul cursor-pointer dark:text-beige2">
                        ¿Las clases son presenciales o online?
                    </summary>
                    <p class="mt-2 text-gray-700 dark:text-slate-100">
                        Las clases son totalmente online, a través de videollamada.
                        Podrás conectarte cómodamente desde casa, sin desplazamientos, y disfrutar de una atención
                        personalizada en tiempo real.
                    </p>
                </details>

                <details class="bg-white rounded-lg shadow p-4 dark:bg-slate-900">
                    <summary class="font-semibold text-azul cursor-pointer dark:text-beige2">¿Qué niveles ofrecéis?</summary>
                    <p class="mt-2 text-gray-700 dark:text-slate-100">
                        Desde A1 hasta C2, con programas adaptados a cada objetivo.
                    </p>
                </details>

                <details class="bg-white rounded-lg shadow p-4 dark:bg-slate-900">
                    <summary class="font-semibold text-azul cursor-pointer dark:text-beige2">
                        ¿Las clases son individuales o en grupo?
                    </summary>
                    <p class="mt-2 text-gray-700 dark:text-slate-100">
                        Actualmente imparto clases individuales, para ofrecer un aprendizaje adaptado a tu nivel, ritmo y objetivos.
                    </p>
                </details>

                <details class="bg-white rounded-lg shadow p-4 dark:bg-slate-900">
                    <summary class="font-semibold text-azul cursor-pointer dark:text-beige2">
                        ¿Cómo puedo reservar una clase?
                    </summary>
                    <p class="mt-2 text-gray-700 dark:text-slate-100">
                        Desde la sección <strong>Clases</strong> de la web puedes acceder al formulario de reserva.
                        Selecciona día y hora y recibirás confirmación por correo electrónico.
                    </p>
                </details>

                <details class="bg-white rounded-lg shadow p-4 dark:bg-slate-900">
                    <summary class="font-semibold text-azul cursor-pointer dark:text-beige2">
                        ¿Puedo cambiar o cancelar una clase reservada?
                    </summary>
                    <p class="mt-2 text-gray-700 dark:text-slate-100">
                        Sí, puedes <strong>reprogramar hasta 2 veces</strong> cada clase con
                        <strong>≥ 24 h de antelación</strong>.  
                        Si cancelas con menos tiempo, la clase se considera impartida.
                    </p>
                </details>

                <details class="bg-white rounded-lg shadow p-4 dark:bg-slate-900">
                    <summary class="font-semibold text-azul cursor-pointer dark:text-beige2">
                        ¿En qué formatos puedo enviar mis documentos?
                    </summary>
                    <p class="mt-2 text-gray-700 dark:text-slate-100">
                        Puedes adjuntar archivos en <strong>PDF, DOCX, ODT o TXT</strong> desde el formulario de traducción.
                    </p>
                </details>

                <details class="bg-white rounded-lg shadow p-4 dark:bg-slate-900">
                    <summary class="font-semibold text-azul cursor-pointer dark:text-beige2">
                        ¿Qué tipos de textos traduces?
                    </summary>
                    <p class="mt-2 text-gray-700 dark:text-slate-100">
                        Trabajo con textos jurídicos, médicos, académicos, audiovisuales, y traducción general y profesional.
                    </p>
                </details>

                <details class="bg-white rounded-lg shadow p-4 dark:bg-slate-900">
                    <summary class="font-semibold text-azul cursor-pointer dark:text-beige2">
                        ¿Cuánto tarda una traducción?
                    </summary>
                    <p class="mt-2 text-gray-700 dark:text-slate-100">
                        Depende del tipo y extensión del documento.  
                        Te enviaré un presupuesto con plazo estimado tras revisar tu solicitud.
                    </p>
                </details>

                <details class="bg-white rounded-lg shadow p-4 dark:bg-slate-900">
                    <summary class="font-semibold text-azul cursor-pointer dark:text-beige2">
                        ¿Mis documentos se tratan de forma confidencial?
                    </summary>
                    <p class="mt-2 text-gray-700 dark:text-slate-100">
                        Sí. Todos los archivos se gestionan con estricta confidencialidad según la normativa vigente.
                    </p>
                </details>

                <details class="bg-white rounded-lg shadow p-4 dark:bg-slate-900">
                    <summary class="font-semibold text-azul cursor-pointer dark:text-beige2">
                        ¿Ofreces servicios de interpretación además de traducción?
                    </summary>
                    <p class="mt-2 text-gray-700 dark:text-slate-100">
                        Sí. Ofrezco interpretación consecutiva y simultánea por videollamada para reuniones, entrevistas y eventos online.
                    </p>
                </details>

            </div>
        </div>
    </section>

    {{-- CTA FINAL --}}
    <section id="cta-final" class="relative py-24 dark:bg-slate-900">

        {{-- Imagen de fondo --}}
        <div class="absolute inset-0">
            <img src="{{ asset('images/panoramicaChica.jpg') }}" alt="panoramicaChica"
                class="w-full h-full object-cover object-top brightness-75 dark:brightness-50">
        </div>

        <div class="relative container mx-auto px-6 text-left text-white">
            <h2 class="text-3xl md:text-4xl font-semibold mb-4">¿Más dudas?</h2>
            <p class="text-lg mb-10 max-w-2xl text-white/90">
                Si aún tienes preguntas o quieres saber más sobre las clases o las traducciones,
                elige la opción que mejor se adapte a lo que buscas.
            </p>

            <div class="flex flex-wrap justify-left gap-4">

                <a href="{{ route('contact.create') }}" class="btn-primary">
                    Contacto
                </a>

                <a href="{{ route('clases') }}" class="btn-secondary">
                    Clases
                </a>

                <a href="{{ route('traducciones') }}"
                    class="bg-beige2 text-azul font-semibold px-8 py-3 rounded-xl shadow-md hover:bg-azul hover:text-white hover:shadow-lg transition dark:bg-slate-800 dark:text-beige2 dark:hover:bg-rojo">
                    Traducciones
                </a>

            </div>
        </div>
    </section>

@endsection

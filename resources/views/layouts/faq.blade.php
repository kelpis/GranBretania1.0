@extends('layouts.site')

@section('title', 'FAQ · Gran Bretania')

@section('content')

    <section id="faq" class="bg-beige2 py-16">
        <div class="container mx-auto px-4 max-w-3xl">
            <h2 class="text-azul text-3xl font-semibold mb-10 text-center">Preguntas frecuentes</h2>

            <div class="space-y-4">
                <details class="bg-white rounded-lg shadow p-4">
                    <summary class="font-semibold text-azul cursor-pointer">¿Las clases son presenciales o online?</summary>
                    <p class="mt-2 text-gray-700">Las clases son totalmente online, a través de videollamada.
                        Podrás conectarte cómodamente desde casa, sin desplazamientos, y disfrutar de una atención
                        personalizada en tiempo real.</p>
                </details>

                <details class="bg-white rounded-lg shadow p-4">
                    <summary class="font-semibold text-azul cursor-pointer">¿Qué niveles ofrecéis?</summary>
                    <p class="mt-2 text-gray-700">Desde A1 hasta C2, con programas adaptados a cada objetivo.</p>
                </details>

                <details class="bg-white rounded-lg shadow p-4">
                    <summary class="font-semibold text-azul cursor-pointer">¿Las clases son individuales o en grupo?
                    </summary>
                    <p class="mt-2 text-gray-700">Actualmente imparto clases individuales, para ofrecer un aprendizaje
                        adaptado a tu nivel, ritmo y objetivos.</p>
                </details>

                <details class="bg-white rounded-lg shadow p-4">
                    <summary class="font-semibold text-azul cursor-pointer">¿Cómo puedo reservar una clase?</summary>
                    <p class="mt-2 text-gray-700">Desde la sección <strong>Clases</strong> de la web puedes acceder al
                        formulario de reserva. Solo tienes que seleccionar la fecha y hora que prefieras, y recibirás una
                        confirmación por correo electrónico.</p>
                </details>

                <details class="bg-white rounded-lg shadow p-4">
                    <summary class="font-semibold text-azul cursor-pointer">
                        ¿Puedo cambiar o cancelar una clase reservada?
                    </summary>
                    <p class="mt-2 text-gray-700">
                        Si ,puedes <strong>reprogramar hasta 2 veces</strong> cada clase con
                        <strong>≥ 24 h de antelación</strong>. Si cancelas o modificas con menos de 24 h, la clase se
                        considera realizada y <strong>no se devuelve el importe</strong>.
                    </p>
                </details>

                <details class="bg-white rounded-lg shadow p-4">
                    <summary class="font-semibold text-azul cursor-pointer">¿En qué formatos puedo enviar mis documentos?
                    </summary>
                    <p class="mt-2 text-gray-700">Puedes adjuntar archivos en <strong>formato PDF, DOCX, ODT o TXT</strong>
                        directamente desde el formulario de traducción.</p>
                </details>

                <details class="bg-white rounded-lg shadow p-4">
                    <summary class="font-semibold text-azul cursor-pointer">¿Qué tipos de textos traduces?</summary>
                    <p class="mt-2 text-gray-700">Trabajo con textos de carácter jurídico, médico, académico y audiovisual,
                        además de traducciones generales y profesionales.</p>
                </details>

                <details class="bg-white rounded-lg shadow p-4">
                    <summary class="font-semibold text-azul cursor-pointer">¿Cuánto tarda una traducción?</summary>
                    <p class="mt-2 text-gray-700">Depende del tipo y extensión del texto.
                        Al recibir tu solicitud te enviaré un presupuesto personalizado con el plazo estimado de entrega.
                    </p>
                </details>

                <details class="bg-white rounded-lg shadow p-4">
                    <summary class="font-semibold text-azul cursor-pointer">¿Mis documentos se tratan de forma confidencial?
                    </summary>
                    <p class="mt-2 text-gray-700">Sí. Todos los archivos y datos personales se manejan con total
                        confidencialidad y conforme a la normativa de protección de datos vigente.</p>
                </details>
            </div>
        </div>
    </section>

    {{-- BLOQUE CTA FINAL --}}
    <section id="cta-final" class="relative py-24">
        {{-- Imagen de fondo --}}
        <div class="absolute inset-0">
            <img src="{{ asset('images/panoramicaChica.jpg') }}" {{-- Cambia por tu imagen --}}
                alt="panoramicaChica" class="w-full h-full object-cover object-top brightness-75">
        </div>

       

 
        <div class="relative container mx-auto px-6 text-left text-white">
            <h2 class="text-3xl md:text-4xl font-semibold mb-4">¿Más dudas?</h2>
            <p class="text-lg mb-10 max-w-2xl text-white/90">
                Si aún tienes preguntas o quieres saber más sobre las clases o las traducciones,
                elige la opción que mejor se adapte a lo que buscas.
            </p>

            {{-- Botones de acción --}}
            <div class="flex flex-wrap justify-left gap-4">
                <a href="{{ route('contact.create') }}"
                    class="btn-primary">
                    Contacto
                </a>
                <a href="{{ route('clases') }}"
                    class="btn-secondary">
                    Clases
                </a>
                <a href="{{ route('traducciones') }}"
                    class="bg-beige2 text-azul font-semibold px-8 py-3 rounded-xl shadow-md hover:bg-azul hover:text-white hover:shadow-lg transition">
                    Traducciones
                </a>
            </div>
        </div>
    </section>

@endsection
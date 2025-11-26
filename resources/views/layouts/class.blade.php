{{--
    Layout: class.blade.php
    Propósito: página informativa sobre las clases (tarifas, proceso, CTA).
    Notas: recurso visual y enlaces a reservas; mantener la estructura de secciones para SEO.
--}}

@extends('layouts.site')

@section('title', 'Clases · Gran Bretania')

@section('content')

    {{-- SECCIÓN: A quién van orientadas las clases --}}
    <section id="publico" class="py-20 bg-beige2 dark:bg-slate-950">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-azul text-3xl md:text-4xl font-semibold mb-4 dark:text-beige2">
                Inglés para todas las edades y perfiles
            </h2>
            <p class="text-gray-700 max-w-2xl mx-auto mb-12 dark:text-slate-100">
                Las clases se adaptan a cada perfil: desde niños que comienzan con el idioma hasta profesionales que buscan
                comunicarse con confianza en el ámbito laboral.
            </p>

            {{-- Tarjetas del público objetivo --}}
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">

                {{-- Niños --}}
                <article class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transition dark:bg-slate-900">
                    <img src="{{ asset('images/nina.jpg') }}" alt="Clases de inglés para niños"
                        class="w-full h-56 object-cover">
                    <div class="p-6">
                        <h3 class="text-azul text-xl font-semibold mb-2 dark:text-beige2">Niños y niñas (desde 8 años)</h3>
                        <p class="text-gray-700 text-sm leading-relaxed dark:text-slate-100">
                            Aprendizaje divertido y participativo. Juegos, pronunciación y vocabulario para construir una
                            base sólida.
                        </p>
                    </div>
                </article>

                {{-- Estudiantes / Adultos --}}
                <article class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transition dark:bg-slate-900">
                    <img src="{{ asset('images/alumnos.png') }}" alt="Clases de inglés para estudiantes y adultos"
                        class="w-full h-56 object-cover">
                    <div class="p-6">
                        <h3 class="text-azul text-xl font-semibold mb-2 dark:text-beige2">Estudiantes y adultos</h3>
                        <p class="text-gray-700 text-sm leading-relaxed dark:text-slate-100">
                            Refuerzo académico, preparación de exámenes oficiales o mejora de fluidez oral. Planes ajustados
                            a cada nivel.
                        </p>
                    </div>
                </article>

                {{-- Empresas --}}
                <article class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transition dark:bg-slate-900">
                    <img src="{{ asset('images/gente-trabajo.jpg') }}" alt="Formación de inglés para empresas"
                        class="w-full h-56 object-cover">
                    <div class="p-6">
                        <h3 class="text-azul text-xl font-semibold mb-2 dark:text-beige2">Empresas y profesionales</h3>
                        <p class="text-gray-700 text-sm leading-relaxed dark:text-slate-100">
                            Formación in-company centrada en comunicación laboral: reuniones, presentaciones y entrevistas
                            en inglés.
                        </p>
                    </div>
                </article>

            </div>

            {{-- CTA opcional --}}
            <div class="mt-12 flex flex-wrap justify-center gap-4">
                <a href="{{ route('contact.create', ['subject' => 'Clase de prueba']) }}" class="btn-primary">Solicitar
                    clase
                    de prueba</a>
                <a href="{{ route('bookings.create') }}"
                    class="btn-three text-white">Reservar clases</a>
            </div>
        </div>
    </section>

    <div class="order-1 lg:order-2 text-center mx-auto max-w-2xl">
        <h2 class="text-azul text-3xl md:text-4xl font-semibold dark:text-beige2">Clases diseñadas según tu meta</h2>
        <p class="mt-4 text-gray-700 dark:text-slate-100">
            Definimos tu <strong>objetivo</strong> y diseñamos un plan a medida: certificación oficial, mejora de la
            conversación, refuerzo integral o competencias para el mundo laboral.
        </p>
    </div>

    <section class="mx-auto max-w-7xl px-4 py-12">
        <div class="grid gap-6 md:grid-cols-3">

            <figure class="overflow-hidden rounded-3xl md:row-span-2 bg-gray-200 max-h-96 dark:bg-slate-800">
                <img src="images/claseOnline.jpg" alt="Clases de inglés personalizadas"
                    class="h-full w-full object-cover object-top" />
            </figure>

            <!-- Bloques superiores derecha -->
            <article class="rounded-2xl border-2 border-azul bg-white p-6 dark:bg-slate-900">
                <h3 class="font-semibold text-azul dark:text-beige2">Certificados y exámenes</h3>
                <ul class="mt-2 text-gray-700 text-sm list-disc ml-5 dark:text-slate-100">
                    <li>Cambridge (A2–C2)</li>
                    <li>IELTS</li>
                    <li>TOEFL</li>
                    <li>OTE</li>
                </ul>
            </article>

            <article class="rounded-2xl border-2 border-rose-400 bg-white p-6 dark:bg-slate-900">
                <h3 class="font-semibold text-azul dark:text-beige2">Conversación práctica</h3>
                <ul class="mt-2 text-gray-700 text-sm list-disc ml-5 dark:text-slate-100">
                    <li>Fluidez</li>
                    <li>Naturalidad</li>
                    <li>Pronunciación</li>
                    <li>Entonación</li>
                </ul>
            </article>

            <!-- Bloques inferiores derecha -->
            <article class="rounded-2xl border-2 border-green-400 bg-white p-6 dark:bg-slate-900">
                <h3 class="font-semibold text-azul dark:text-beige2">Refuerzo general</h3>
                <ul class="mt-2 text-gray-700 text-sm list-disc ml-5 dark:text-slate-100">
                    <li>Gramática</li>
                    <li>Vocabulario</li>
                    <li>Comprensión</li>
                    <li>Writing</li>
                </ul>
            </article>

            <article class="rounded-2xl border-2 border-amber-400 bg-white p-6 dark:bg-slate-900">
                <h3 class="font-semibold text-azul dark:text-beige2">Inglés profesional</h3>
                <ul class="mt-2 text-gray-700 text-sm list-disc ml-5 dark:text-slate-100">
                    <li>Trabajo</li>
                    <li>Presentaciones</li>
                    <li>Entrevistas</li>
                    <li>Terminología sectorial</li>
                </ul>
            </article>

        </div>
    </section>

    <div class="mt-16 lg:mt-6 lg:top-6 bg-azul py-8 rounded-2xl shadow relative bg-cover bg-center">
        <div class="mx-auto max-w-md bg-beige2 rounded-2xl p-6 dark:bg-slate-900">
            <h3 class="text-azul text-xl font-semibold text-center dark:text-beige2">Tarifas</h3>

            <div class="mt-5 grid grid-cols-2 gap-3 text-sm">
                <div class="rounded-xl border border-azul/20 p-4 text-center bg-white dark:bg-slate-800">
                    <div class="text-2xl font-semibold text-azul dark:text-beige2">25 €</div>
                    <div class="text-gray-600 dark:text-slate-200">por clase</div>
                </div>
                <div class="rounded-xl border border-azul/20 p-4 text-center bg-white dark:bg-slate-800">
                    <div class="text-2xl font-semibold text-azul dark:text-beige2">1 h</div>
                    <div class="text-gray-600 dark:text-slate-200">por sesión</div>
                </div>
            </div>

            <ul class="mt-5 space-y-2 text-gray-700 text-sm dark:text-slate-100">
                <li>🎥 En directo por <strong>Google Meet</strong></li>
                <li>👥 <strong>Grupos</strong>: <a href="{{ route('contact.create', ['subject' => 'Tarifa grupos']) }}"
                        class="text-azul underline hover:text-rojo dark:text-beige2">consultar</a></li>
                <li>🔁 Reprogr. <strong>2 veces</strong> con <strong>≥24 h</strong></li>
                <li>⚠️ <strong>&lt;24 h</strong>: sin devolución</li>
            </ul>

            <div class="mt-6 flex gap-3">
                <a href="{{ route('bookings.create') . '?subject=' . urlencode('Reservar clase') }}"
                    class="btn-primary flex-1 text-center">Reservar</a>
                <a href="{{ route('contact.create', ['subject' => 'Clase de prueba']) }}"
                    class="btn-three text-white flex-1 text-center">Clase de prueba</a>
            </div>
        </div>
    </div>

    {{-- SECCIÓN: Proceso --}}
    <section id="proceso" class="py-16 bg-beige2 dark:bg-slate-950">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-azul text-3xl md:text-4xl font-semibold mb-4 dark:text-beige2">¿Cómo empezar a aprender?</h2>
            <p class="text-gray-700 max-w-2xl mx-auto mb-10 dark:text-slate-100">
                Sigue estos pasos y tendrás tu primera clase online en marcha.
            </p>

            {{-- Proceso horizontal --}}
            <div class="relative flex flex-col md:flex-row justify-between items-center md:items-start gap-10 md:gap-0">

                <div class="hidden md:block absolute top-8 left-0 w-full h-0.5 bg-gray-200 z-0 dark:bg-slate-700"></div>

                {{-- Paso 1 --}}
                <div class="relative z-10 flex-1 max-w-[250px]">
                    <div class="flex flex-col items-center md:items-center">
                        <div
                            class="flex items-center justify-center w-10 h-10 rounded-full bg-azul text-white font-semibold text-sm">
                            1</div>
                        <h3 class="text-azul font-semibold mt-3 dark:text-beige2">Regístrate</h3>
                        <p class="text-gray-700 text-sm mt-2 dark:text-slate-100">
                            Crea tu cuenta para acceder al área de usuario.
                        </p>
                        <a href="{{ route('register') }}" class="btn-three text-white mt-3">Crear cuenta</a>
                    </div>
                </div>

                {{-- Paso 2 --}}
                <div class="relative z-10 flex-1 max-w-[250px]">
                    <div class="flex flex-col items-center md:items-center">
                        <div
                            class="flex items-center justify-center w-10 h-10 rounded-full bg-azul text-white font-semibold text-sm">
                            2</div>
                        <h3 class="text-azul font-semibold mt-3 dark:text-beige2">Reserva tu clase</h3>
                        <p class="text-gray-700 text-sm mt-2 dark:text-slate-100">
                            Completa el formulario y elige el día y la hora.
                        </p>
                        <a href="{{ route('bookings.create') }}" class="btn-primary mt-3">Reservar clase</a>
                    </div>
                </div>

                {{-- Paso 3 --}}
                <div class="relative z-10 flex-1 max-w-[250px]">
                    <div class="flex flex-col items-center md:items-center">
                        <div
                            class="flex items-center justify-center w-10 h-10 rounded-full bg-azul text-white font-semibold text-sm">
                            3</div>
                        <h3 class="text-azul font-semibold mt-3 dark:text-beige2">Confirma tu reserva</h3>
                        <p class="text-gray-700 text-sm mt-2 dark:text-slate-100">
                            Recibirás un email de confirmación con los detalles.
                        </p>
                    </div>
                </div>

                {{-- Paso 4 --}}
                <div class="relative z-10 flex-1 max-w-[250px]">
                    <div class="flex flex-col items-center md:items-center">
                        <div
                            class="flex items-center justify-center w-10 h-10 rounded-full bg-azul text-white font-semibold text-sm">
                            4</div>
                        <h3 class="text-azul font-semibold mt-3 dark:text-beige2">Accede a tu clase</h3>
                        <p class="text-gray-700 text-sm mt-2 dark:text-slate-100">
                            Recibirás un correo con el enlace de <strong>Google Meet</strong>.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Aviso / política rápida --}}
            <p class="mt-10 text-sm text-gray-600 max-w-2xl mx-auto dark:text-slate-300">
                <strong>Recuerda:</strong> puedes reprogramar hasta 2 veces cada clase con al menos 24 h de antelación.
                Si cancelas con menos tiempo, la clase se considera impartida.
            </p>

        </div>
    </section>

@endsection

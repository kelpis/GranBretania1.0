@extends('layouts.site')

@section('title', 'Condiciones de los servicios · Gran Bretania')

@section('content')

<section class="container mx-auto px-4 py-10">

    {{-- TÍTULO --}}
    <header class="max-w-3xl mb-8">
        <h1 class="text-azul text-3xl md:text-4xl font-semibold mb-3 dark:text-beige2">Condiciones del servicio</h1>
        <p class="text-gray-700 dark:text-slate-100">
            Las presentes condiciones regulan la contratación y uso de los servicios ofrecidos por
            <strong>Gran Bretania</strong>, incluyendo clases de inglés online, traducciones profesionales e interpretación remota.
        </p>
    </header>

    <div class="grid lg:grid-cols-4 gap-10">

        {{-- ÍNDICE --}}
        <aside class="lg:col-span-1">
            <div class="bg-beige2 rounded-2xl p-4 text-sm sticky top-24 
                        dark:bg-slate-800 dark:text-slate-100">
                <h2 class="text-azul font-semibold mb-3 text-base dark:text-beige2">Contenido</h2>
                <ul class="space-y-2">
                    <li><a href="#objeto" class="hover:text-azul dark:hover:text-beige2">1. Objeto</a></li>
                    <li><a href="#lssi" class="hover:text-azul dark:hover:text-beige2">2. Cumplimiento LSSI-CE</a></li>
                    <li><a href="#pagos" class="hover:text-azul dark:hover:text-beige2">3. Pagos</a></li>
                    <li><a href="#reservas" class="hover:text-azul dark:hover:text-beige2">4. Condiciones de reserva</a></li>
                    <li><a href="#traducciones" class="hover:text-azul dark:hover:text-beige2">5. Traducciones</a></li>
                    <li><a href="#interpretacion" class="hover:text-azul dark:hover:text-beige2">6. Interpretación</a></li>
                    <li><a href="#reembolsos" class="hover:text-azul dark:hover:text-beige2">7. Reembolsos</a></li>
                    <li><a href="#responsabilidad" class="hover:text-azul dark:hover:text-beige2">8. Responsabilidad</a></li>
                    <li><a href="#aceptacion" class="hover:text-azul dark:hover:text-beige2">9. Aceptación</a></li>
                    <li><a href="#contacto" class="hover:text-azul dark:hover:text-beige2">Contacto</a></li>
                </ul>
            </div>
        </aside>

        {{-- CONTENIDO --}}
        <div class="lg:col-span-3 space-y-8 text-sm md:text-base leading-relaxed text-gray-800 dark:text-slate-100">

            {{-- 1. OBJETO --}}
            <section id="objeto" class="bg-white rounded-2xl shadow p-6 dark:bg-slate-900">
                <h2 class="text-azul font-semibold text-lg mb-3 dark:text-beige2">1. Objeto</h2>
                <p>
                    Las presentes condiciones regulan la contratación y uso de los servicios ofrecidos por
                    <strong>Gran Bretania</strong>, incluyendo clases de inglés online, traducciones
                    profesionales e interpretación remota.
                </p>
            </section>

            {{-- 2. LSSI --}}
            <section id="lssi" class="bg-white rounded-2xl shadow p-6 dark:bg-slate-900">
                <h2 class="text-azul font-semibold text-lg mb-3 dark:text-beige2">2. Cumplimiento de la LSSI-CE</h2>
                <p>
                    Gran Bretania cumple con la
                    <strong>Ley 34/2002, de Servicios de la Sociedad de la Información y de Comercio Electrónico (LSSI-CE)</strong>,
                    que regula la información al usuario, el uso de cookies, las comunicaciones comerciales
                    y las condiciones de contratación electrónica.
                </p>
            </section>

            {{-- 3. PAGOS --}}
            <section id="pagos" class="bg-white rounded-2xl shadow p-6 dark:bg-slate-900">
                <h2 class="text-azul font-semibold text-lg mb-3 dark:text-beige2">3. Pagos y pasarela de pago segura</h2>
                <p class="mb-2">
                    Los pagos de los servicios se gestionan a través de la plataforma <strong>Stripe</strong>,
                    proveedor internacional de pagos seguro y certificado PCI-DSS.
                </p>
                <p class="mb-2">
                    <strong>Gran Bretania no almacena, procesa ni tiene acceso en ningún momento a los datos bancarios del
                        usuario</strong>.
                    Todos los datos de tarjeta se introducen y procesan exclusivamente en los servidores seguros de Stripe.
                </p>
                <p class="text-sm text-gray-600 dark:text-slate-400">
                    Más información en:
                    <a href="https://stripe.com/es/privacy" target="_blank"
                        class="text-azul underline dark:text-beige2 dark:hover:text-rojo">Política de privacidad de Stripe</a>.
                </p>
            </section>

            {{-- 4. RESERVAS --}}
            <section id="reservas" class="bg-white rounded-2xl shadow p-6 dark:bg-slate-900">
                <h2 class="text-azul font-semibold text-lg mb-3 dark:text-beige2">4. Condiciones de reserva (clases de inglés)</h2>
                <ul class="list-disc ml-5 space-y-2">
                    <li>Las clases se imparten online a través de <strong>Google Meet</strong>.</li>
                    <li>Tienen una duración de <strong>60 minutos</strong>.</li>
                    <li>La reserva se confirma mediante pago previo.</li>
                    <li>El usuario puede <strong>reprogramar hasta 2 veces</strong> la clase desde su área de usuario con una antelacion de al menos 24 horas.</li>
                    <li>Las cancelaciones con menos de <strong>24 horas</strong> no dan derecho a reembolso.</li>
                    <li>Las cancelaciones con más de 24 h permiten reprogramación sin coste.</li>
                </ul>

                <h3 class="text-azul font-semibold text-base mt-4 mb-1 dark:text-beige2">Clases de prueba</h3>
                <ul class="list-disc ml-5 space-y-2">
                    <li>Las clases de prueba tienen una duración de <strong>20 minutos</strong>.</li>
                    <li>Son totalmente gratuitas.</li>
                    <li>Se reservan exclusivamente a través del
                        <a href="{{ route('contact.create') }}" class="text-azul underline dark:text-beige2 dark:hover:text-rojo">formulario de contacto</a>.
                    </li>
                    <li>No requieren pago previo.</li>
                </ul>
            </section>

            {{-- 5. TRADUCCIONES --}}
            <section id="traducciones" class="bg-white rounded-2xl shadow p-6 dark:bg-slate-900">
                <h2 class="text-azul font-semibold text-lg mb-3 dark:text-beige2">5. Condiciones del servicio de traducción</h2>
                <ul class="list-disc ml-5 space-y-2">
                    <li>El presupuesto se calcula <strong>por palabra</strong> del documento original.</li>
                    <li>El usuario debe indicar idioma de origen y destino (ES, EN o FR).</li>
                    <li>
                        Los documentos se envían exclusivamente a través del formulario seguro del sitio web. Tras la recepción del documento,
                        el usuario recibirá un correo electrónico con el presupuesto y un enlace de pago. Una vez efectuado el pago,
                        se procederá a la traducción y el documento final será enviado por correo electrónico.
                    </li>
                    <li>
                        Los documentos se almacenan temporalmente siguiendo la
                        <a href="{{ route('privacy') }}" class="text-azul underline dark:text-beige2 dark:hover:text-rojo">Política de privacidad</a>.
                    </li>
                    <li>Las entregas se realizan por correo electrónico en el plazo acordado.</li>
                    <li>En traducciones urgentes puede aplicarse un recargo.</li>
                </ul>
            </section>

            {{-- 6. INTERPRETACIÓN --}}
            <section id="interpretacion" class="bg-white rounded-2xl shadow p-6 dark:bg-slate-900">
                <h2 class="text-azul font-semibold text-lg mb-3 dark:text-beige2">6. Condiciones del servicio de interpretación</h2>
                <ul class="list-disc ml-5 space-y-2">
                    <li>La interpretación es exclusivamente <strong>inglés ↔ español</strong>.</li>
                    <li>Se realiza mediante Google Meet.</li>
                    <li>Es obligatorio facilitar agenda, temática y terminología base.</li>
                    <li>Las cancelaciones con menos de 48 h no tienen reembolso.</li>
                </ul>
            </section>

            {{-- 7. REEMBOLSOS --}}
            <section id="reembolsos" class="bg-white rounded-2xl shadow p-6 dark:bg-slate-900">
                <h2 class="text-azul font-semibold text-lg mb-3 dark:text-beige2">7. Política de reembolsos</h2>
                <p>
                    Los reembolsos se realizarán únicamente cuando el servicio no pueda prestarse por causa imputable a
                    Gran Bretania, o cuando la cancelación se solicite dentro de los plazos permitidos.
                </p>
            </section>

            {{-- 8. RESPONSABILIDAD --}}
            <section id="responsabilidad" class="bg-white rounded-2xl shadow p-6 dark:bg-slate-900">
                <h2 class="text-azul font-semibold text-lg mb-3 dark:text-beige2">8. Limitación de responsabilidad</h2>
                <p>
                    Gran Bretania no se hace responsable del uso indebido de los contenidos,
                    de fallos técnicos temporales o de incidencias ajenas a su control en servicios de terceros
                    como Stripe, Google Meet o proveedores de hosting.
                </p>
            </section>

            {{-- 9. ACEPTACIÓN --}}
            <section id="aceptacion" class="bg-white rounded-2xl shadow p-6 dark:bg-slate-900">
                <h2 class="text-azul font-semibold text-lg mb-3 dark:text-beige2">9. Aceptación de las condiciones</h2>
                <p>
                    La contratación de cualquier servicio implica la aceptación completa de estas condiciones.
                </p>
            </section>

            {{-- CONTACTO --}}
            <section id="contacto" class="bg-white rounded-2xl shadow p-6 dark:bg-slate-900">
                <h2 class="text-azul font-semibold text-lg mb-3 dark:text-beige2">Contacto</h2>
                <p>
                    Para cualquier consulta relacionada con estas condiciones:
                    <a href="mailto:info@granbretania.com" class="text-azul underline dark:text-beige2">info@granbretania.com</a>
                    o utiliza el <a href="{{ route('contact.create') }}" class="text-azul underline dark:text-beige2">formulario de contacto</a>.
                </p>
            </section>

        </div>
    </div>
</section>

@endsection
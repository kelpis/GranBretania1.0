@extends('layouts.site')

@section('title', 'Condiciones de los servicios · Gran Bretania')

@section('content')
    <section id="condiciones-servicio" class="bg-white rounded-2xl shadow p-6">
        <h2 class="text-azul font-semibold text-xl mb-4">Condiciones del servicio</h2>

        {{-- 1. Objeto --}}
        <h3 class="text-azul font-semibold text-lg mt-6 mb-2">1. Objeto</h3>
        <p class="text-gray-800">
            Las presentes condiciones regulan la contratación y uso de los servicios ofrecidos por
            <strong>Gran Bretania</strong>, incluyendo clases de inglés online, traducciones
            profesionales e interpretación remota.
        </p>

        {{-- 2. Cumplimiento LSSI-CE --}}
        <h3 class="text-azul font-semibold text-lg mt-6 mb-2">2. Cumplimiento de la LSSI-CE</h3>
        <p class="text-gray-800">
            Gran Bretania cumple con la
            <strong>Ley 34/2002, de Servicios de la Sociedad de la Información y de Comercio Electrónico (LSSI-CE)</strong>,
            que regula la información al usuario, el uso de cookies, las comunicaciones comerciales
            y las condiciones de contratación electrónica.
        </p>

        {{-- 3. Pagos y pasarela segura Stripe --}}
        <h3 class="text-azul font-semibold text-lg mt-6 mb-2">3. Pagos y pasarela de pago segura</h3>
        <p class="text-gray-800 mb-2">
            Los pagos de los servicios se gestionan a través de la plataforma <strong>Stripe</strong>,
            proveedor internacional de pagos seguro y certificado PCI-DSS.
        </p>
        <p class="text-gray-800 mb-2">
            <strong>Gran Bretania no almacena, procesa ni tiene acceso en ningún momento a los datos bancarios del
                usuario</strong>.
            Todos los datos de tarjeta se introducen y procesan exclusivamente en los servidores seguros de Stripe.
        </p>
        <p class="text-gray-800 text-sm">
            Más información en: <a href="https://stripe.com/es/privacy" target="_blank" class="text-azul underline">Política
                de privacidad de Stripe</a>.
        </p>

        {{-- 4. Condiciones de reserva (clases de inglés) --}}
        <h3 class="text-azul font-semibold text-lg mt-6 mb-2">4. Condiciones de reserva (clases de inglés)</h3>
        <ul class="list-disc ml-5 space-y-2 text-gray-800">
            <li>Las clases se imparten online a través de <strong>Google Meet</strong>.</li>
            <li>Tienen una duración de <strong>60 minutos</strong>.</li>
            <li>La reserva se confirma mediante pago previo.</li>
            <li>El usuario puede <strong>reprogramar hasta 2 veces</strong> la clase desde su área de usuario.</li>
            <li>Las cancelaciones con menos de <strong>24 horas</strong> no dan derecho a reembolso.</li>
            <li>Las cancelaciones con más de 24 h permiten reprogramación sin coste.</li>
        </ul>

        {{-- 5. Condiciones para traducciones --}}
        <h3 class="text-azul font-semibold text-lg mt-6 mb-2">5. Condiciones del servicio de traducción</h3>
        <ul class="list-disc ml-5 space-y-2 text-gray-800">
            <li>El presupuesto se calcula <strong>por palabra</strong> del documento original.</li>
            <li>El usuario debe indicar idioma de origen y destino (ES, EN o FR).</li>
            <li>Los documentos se envían a través del formulario seguro del sitio web.</li>
            <li>Los documentos se almacenan temporalmente siguiendo la
                <a href="{{ route('privacy') }}" class="text-azul underline">Política de privacidad</a>.
            </li>
            <li>Las entregas se realizan por correo electrónico en el plazo acordado.</li>
            <li>En traducciones urgentes puede aplicarse un recargo.</li>
        </ul>

        {{-- 6. Interpretación --}}
        <h3 class="text-azul font-semibold text-lg mt-6 mb-2">6. Condiciones del servicio de interpretación</h3>
        <ul class="list-disc ml-5 space-y-2 text-gray-800">
            <li>La interpretación es exclusivamente <strong>inglés ↔ español</strong>.</li>
            <li>Se realiza mediante Google Meet, Zoom o Microsoft Teams.</li>
            <li>Es obligatorio facilitar agenda, temática y terminología base.</li>
            <li>Las cancelaciones con menos de 48 h no tienen reembolso.</li>
        </ul>

        {{-- 7. Reembolsos --}}
        <h3 class="text-azul font-semibold text-lg mt-6 mb-2">7. Política de reembolsos</h3>
        <p class="text-gray-800">
            Los reembolsos se realizarán únicamente cuando el servicio no pueda prestarse por causa imputable a
            Gran Bretania, o cuando la cancelación se solicite dentro de los plazos permitidos.
        </p>

        {{-- 8. Responsabilidad --}}
        <h3 class="text-azul font-semibold text-lg mt-6 mb-2">8. Limitación de responsabilidad</h3>
        <p class="text-gray-800">
            Gran Bretania no se hace responsable del uso indebido de los contenidos,
            de fallos técnicos temporales o de incidencias ajenas a su control en servicios de terceros
            como Stripe, Google Meet o proveedores de hosting.
        </p>

        {{-- 9. Aceptación --}}
        <h3 class="text-azul font-semibold text-lg mt-6 mb-2">9. Aceptación de las condiciones</h3>
        <p class="text-gray-800">
            La contratación de cualquier servicio implica la aceptación completa de estas condiciones.
        </p>
    </section>

@endsection
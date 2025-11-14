@extends('layouts.site')

@section('title', 'Privacidad · Gran Bretania')

@section('content')
  <section class="container mx-auto px-4 py-10">

    {{-- MIGAS DE PAN (componente) --}}
    @php
      $breadcrumbs = [
        ['label' => 'Inicio', 'url' => route('home')],
        ['label' => 'Política de privacidad', 'url' => '',],
      ];
    @endphp

    <x-breadcrumbs :items="$breadcrumbs" />

    {{-- TÍTULO PRINCIPAL --}}
    <header class="max-w-3xl mb-8">
      <h1 class="text-azul text-3xl md:text-4xl font-semibold mb-3">
        Política de privacidad
      </h1>
      <p class="text-gray-700">
        En <strong>Gran Bretania</strong> nos tomamos muy en serio la protección de tus datos personales.
        En esta página te explicamos qué información recopilamos, para qué la utilizamos y cuáles son tus derechos.
      </p>
    </header>

    {{-- LAYOUT DOS COLUMNAS: ÍNDICE + CONTENIDO --}}
    <div class="grid lg:grid-cols-4 gap-10">

      {{-- ÍNDICE LATERAL --}}
      <aside class="lg:col-span-1">
        <div class="bg-beige2 rounded-2xl p-4 text-sm sticky top-24">
          <h2 class="text-azul font-semibold mb-3 text-base">Contenido</h2>
          <ul class="space-y-2">
            <li><a href="#responsable" class="text-gray-700 hover:text-azul">1. Responsable del tratamiento</a></li>
            <li><a href="#datos" class="text-gray-700 hover:text-azul">2. Datos que recopilamos</a></li>
            <li><a href="#finalidad" class="text-gray-700 hover:text-azul">3. Finalidad del tratamiento</a></li>
            <li><a href="#legitimacion" class="text-gray-700 hover:text-azul">4. Legitimación</a></li>
            <li><a href="#conservacion" class="text-gray-700 hover:text-azul">5. Plazos de conservación</a></li>
            <li><a href="#destinatarios" class="text-gray-700 hover:text-azul">6. Destinatarios</a></li>
            <li><a href="#derechos" class="text-gray-700 hover:text-azul">7. Tus derechos</a></li>
            <li><a href="#seguridad" class="text-gray-700 hover:text-azul">8. Seguridad de la información</a></li>
            <li><a href="#cookies" class="text-gray-700 hover:text-azul">9. Cookies</a></li>
            <li><a href="#contacto" class="text-gray-700 hover:text-azul">10. Contacto</a></li>
          </ul>
        </div>
      </aside>

      {{-- CONTENIDO PRINCIPAL --}}
      <div class="lg:col-span-3 space-y-8 text-sm md:text-base leading-relaxed text-gray-800">

        {{-- 1. RESPONSABLE --}}
        <section id="responsable" class="bg-white rounded-2xl shadow p-6">
          <h2 class="text-azul font-semibold text-lg mb-3">1. Responsable del tratamiento</h2>
          <p>
            Responsable: <strong>Gran Bretania</strong> (indicar nombre y apellidos de la profesional o razón social).<br>
            NIF/CIF: (indicar).<br>
            Domicilio: (dirección completa).<br>
            Email de contacto: <a href="mailto:tucorreo@granbretania.com"
              class="text-azul underline">tucorreo@granbretania.com</a>.
          </p>
        </section>

        {{-- 2. DATOS QUE RECOPILAMOS --}}
        <section id="datos" class="bg-white rounded-2xl shadow p-6">
          <h2 class="text-azul font-semibold text-lg mb-3">2. Datos que recopilamos</h2>
          <p class="mb-2">Podemos tratar las siguientes categorías de datos:</p>
          <ul class="list-disc ml-5 space-y-1">
            <li>Datos identificativos (nombre, apellidos).</li>
            <li>Datos de contacto (email, teléfono).</li>
            <li>Datos de facturación (si procede: dirección, NIF).</li>
            <li>Información sobre tu nivel de inglés, objetivos y disponibilidad horaria.</li>
            <li>Documentos que envías para solicitar <strong>traducciones</strong> o <strong>interpretación</strong>.</li>
            <li>Datos de acceso a la cuenta (usuario, contraseña encriptada).</li>
          </ul>
          <p class="mt-3 text-xs text-gray-600">
            No se tratan categorías especiales de datos salvo que el propio contenido de los documentos lo incluya,
            en cuyo caso se aplica un nivel reforzado de confidencialidad.
          </p>
        </section>

        {{-- 3. FINALIDAD --}}
        <section id="finalidad" class="bg-white rounded-2xl shadow p-6">
          <h2 class="text-azul font-semibold text-lg mb-3">3. Finalidad del tratamiento</h2>
          <p class="mb-2">Utilizamos tus datos para:</p>
          <ul class="list-disc ml-5 space-y-1">
            <li>Gestionar tus solicitudes de <strong>clases de inglés online</strong>.</li>
            <li>Elaborar y enviarte presupuestos de <strong>traducción</strong> e <strong>interpretación online</strong>.
            </li>
            <li>Prestar los servicios contratados y hacer seguimiento de tu progreso.</li>
            <li>Gestionar facturación, cobros y obligaciones fiscales.</li>
            <li>Responder a consultas que nos envíes a través del formulario de contacto.</li>
            <li>Enviarte comunicaciones puntuales relacionadas con tus reservas o servicios activos.</li>
          </ul>
        </section>

        {{-- 4. LEGITIMACIÓN --}}
        <section id="legitimacion" class="bg-white rounded-2xl shadow p-6">
          <h2 class="text-azul font-semibold text-lg mb-3">4. Legitimación</h2>
          <p class="mb-2">La base legal para el tratamiento de tus datos es:</p>
          <ul class="list-disc ml-5 space-y-1">
            <li><strong>Ejecución de un contrato</strong> o aplicación de medidas precontractuales (cuando solicitas
              clases, traducciones o interpretación).</li>
            <li><strong>Consentimiento</strong> (cuando marcas la casilla de aceptación de la política de privacidad en
              los formularios).</li>
            <li><strong>Obligación legal</strong> (obligaciones fiscales o contables asociadas a los servicios prestados).
            </li>
          </ul>
        </section>

        {{-- 5. CONSERVACIÓN --}}
        <section id="conservacion" class="bg-white rounded-2xl shadow p-6">
          <h2 class="text-azul font-semibold text-lg mb-3">5. Plazos de conservación</h2>
          <p>
            Conservaremos tus datos mientras exista una relación activa (usuario registrado, servicios en curso)
            y, posteriormente, durante los plazos necesarios para cumplir con las obligaciones legales
            (por ejemplo, plazos fiscales o de responsabilidad profesional).
          </p>
          <p class="mt-2">
            Los documentos aportados para traducción se conservarán el tiempo estrictamente necesario para la prestación
            del servicio
            y la atención de posibles aclaraciones, respetando en todo momento la
            <a href="{{ route('privacy') }}" class="text-azul underline">política de protección de datos</a>.
          </p>
        </section>

        {{-- 6. DESTINATARIOS --}}
        <section id="destinatarios" class="bg-white rounded-2xl shadow p-6">
          <h2 class="text-azul font-semibold text-lg mb-3">6. Destinatarios de los datos</h2>
          <p class="mb-2">
            Con carácter general, tus datos no se ceden a terceros, salvo obligación legal o cuando sea necesario
            para la correcta prestación del servicio. Por ejemplo:
          </p>
          <ul class="list-disc ml-5 space-y-1">
            <li>Proveedor de hosting y correo electrónico.</li>
            <li>Plataformas de pago seguras (si corresponde).</li>
            <li>Asesoría fiscal o contable, en caso necesario.</li>
          </ul>
          <p class="mt-2 text-xs text-gray-600">
            Todos los proveedores utilizan medidas de seguridad adecuadas y no utilizarán tus datos para fines propios.
          </p>
        </section>

        {{-- 7. DERECHOS --}}
        <section id="derechos" class="bg-white rounded-2xl shadow p-6">
          <h2 class="text-azul font-semibold text-lg mb-3">7. Tus derechos</h2>
          <p class="mb-2">
            Puedes ejercer en cualquier momento tus derechos de:
          </p>
          <ul class="list-disc ml-5 space-y-1">
            <li><strong>Acceso</strong> a tus datos personales.</li>
            <li><strong>Rectificación</strong> de datos inexactos o incompletos.</li>
            <li><strong>Supresión</strong> cuando los datos ya no sean necesarios.</li>
            <li><strong>Limitación</strong> del tratamiento en determinados supuestos.</li>
            <li><strong>Oposición</strong> al tratamiento de tus datos.</li>
            <li><strong>Portabilidad</strong> de los datos cuando sea técnicamente posible.</li>
          </ul>
          <p class="mt-3">
            Para ejercer estos derechos, puedes escribir a:
            <a href="mailto:tucorreo@granbretania.com" class="text-azul underline">tucorreo@granbretania.com</a>,
            indicando “Protección de datos” en el asunto y adjuntando una copia de tu documento de identidad.
          </p>
        </section>

        {{-- 8. SEGURIDAD --}}
        <section id="seguridad" class="bg-white rounded-2xl shadow p-6">
          <h2 class="text-azul font-semibold text-lg mb-3">8. Seguridad de la información</h2>
          <p>
            Aplicamos medidas técnicas y organizativas adecuadas para proteger tus datos frente a accesos no autorizados,
            pérdida, destrucción o divulgación. Solo el personal autorizado y, en su caso, colaboradores sujetos a
            confidencialidad pueden acceder a la información estrictamente necesaria para la prestación del servicio.
          </p>
        </section>

        {{-- 9. COOKIES --}}
        <section id="cookies" class="bg-white rounded-2xl shadow p-6">
          <h2 class="text-azul font-semibold text-lg mb-3">9. Cookies</h2>
          <p>
            Este sitio web utiliza cookies técnicas necesarias para su funcionamiento y, en su caso,
            cookies analíticas o de terceros. Puedes obtener más información en nuestra
            <a href="{{ route('cookies.policy') }}" class="text-azul underline">Política de cookies</a>.
          </p>
        </section>

        {{-- 10. CONTACTO --}}
        <section id="contacto" class="bg-white rounded-2xl shadow p-6">
          <h2 class="text-azul font-semibold text-lg mb-3">10. Contacto</h2>
          <p>
            Si tienes dudas sobre esta política o sobre cómo tratamos tus datos, puedes escribirnos a
            <a href="mailto:tucorreo@granbretania.com" class="text-azul underline">tucorreo@granbretania.com</a>
            o utilizar nuestro <a href="{{ route('contact.create') }}" class="text-azul underline">formulario de
              contacto</a>.
          </p>
          <p class="mt-3 text-xs text-gray-600">
            Esta política puede actualizarse para adaptarse a cambios legales o de los servicios ofrecidos.
            Te recomendamos revisarla periódicamente.
          </p>
        </section>

      </div>
    </div>
  </section>
@endsection
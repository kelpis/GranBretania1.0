@extends('layouts.site')

@section('title', 'Cookies · Gran Bretania')

  @section('content')

    <section class="container mx-auto px-4 py-10">

      {{-- MIGAS DE PAN --}}
      <nav class="text-sm text-gray-600 mb-6" aria-label="Breadcrumb">
        <ol class="list-none p-0 inline-flex">
          <li class="flex items-center">
            <a href="{{ route('home') }}" class="text-azul hover:text-rojo">Inicio</a>
            <svg class="w-4 h-4 mx-2 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
          </li>
          <li class="flex items-center text-gray-500" aria-current="page">
            Política de cookies
          </li>
        </ol>
      </nav>

      {{-- TÍTULO PRINCIPAL --}}
      <header class="max-w-3xl mb-8">
        <h1 class="text-azul text-3xl md:text-4xl font-semibold mb-3">
          Política de cookies
        </h1>
        <p class="text-gray-700">
          En <strong>Gran Bretania</strong> utilizamos cookies para mejorar tu experiencia de navegación, analizar el uso
          del sitio y, en su caso, mostrar contenidos adaptados a tus intereses. En esta página te explicamos qué son las
          cookies, qué tipos utilizamos y cómo puedes gestionarlas.
        </p>
      </header>

      {{-- LAYOUT DOS COLUMNAS --}}
      <div class="grid lg:grid-cols-4 gap-10">

        {{-- ÍNDICE LATERAL --}}
        <aside class="lg:col-span-1">
          <div class="bg-beige2 rounded-2xl p-4 text-sm sticky top-24">
            <h2 class="text-azul font-semibold mb-3 text-base">Contenido</h2>
            <ul class="space-y-2">
              <li><a href="#que-son" class="text-gray-700 hover:text-azul">1. ¿Qué son las cookies?</a></li>
              <li><a href="#tipos" class="text-gray-700 hover:text-azul">2. Tipos de cookies utilizadas</a></li>
              <li><a href="#finalidad" class="text-gray-700 hover:text-azul">3. Finalidad de las cookies</a></li>
              <li><a href="#terceros" class="text-gray-700 hover:text-azul">4. Cookies de terceros</a></li>
              <li><a href="#gestion" class="text-gray-700 hover:text-azul">5. Cómo configurar o desactivar cookies</a></li>
              <li><a href="#consecuencias" class="text-gray-700 hover:text-azul">6. ¿Qué ocurre si las desactivas?</a></li>
              <li><a href="#actualizacion" class="text-gray-700 hover:text-azul">7. Actualización de la política de
                  cookies</a></li>
              <li><a href="#contacto" class="text-gray-700 hover:text-azul">8. Contacto</a></li>
            </ul>
          </div>
        </aside>

        {{-- CONTENIDO PRINCIPAL --}}
        <div class="lg:col-span-3 space-y-8 text-sm md:text-base leading-relaxed text-gray-800">

          {{-- 1. QUÉ SON --}}
          <section id="que-son" class="bg-white rounded-2xl shadow p-6">
            <h2 class="text-azul font-semibold text-lg mb-3">1. ¿Qué son las cookies?</h2>
            <p>
              Las cookies son pequeños archivos de texto que se descargan en tu dispositivo cuando visitas
              determinadas páginas web. Permiten, entre otras cosas, recordar tus preferencias de navegación,
              entender cómo utilizas el sitio y, en algunos casos, reconocer tu dispositivo en visitas posteriores.
            </p>
          </section>

          {{-- 2. TIPOS UTILIZADAS --}}
          <section id="tipos" class="bg-white rounded-2xl shadow p-6">
            <h2 class="text-azul font-semibold text-lg mb-3">2. Tipos de cookies utilizadas</h2>
            <p class="mb-2">En <strong>Gran Bretania</strong> podemos utilizar las siguientes categorías de cookies:</p>
            <ul class="list-disc ml-5 space-y-1">
              <li><strong>Cookies técnicas o necesarias</strong>: imprescindibles para el funcionamiento básico del sitio
                (por ejemplo, mantener la sesión iniciada o recordar el contenido de un formulario).</li>
              <li><strong>Cookies de preferencias</strong>: permiten recordar tus ajustes (idioma, región, etc.).</li>
              <li><strong>Cookies estadísticas o de análisis</strong>: nos ayudan a comprender cómo se utiliza la web
                (páginas más visitadas, tiempo de permanencia, etc.) para mejorar contenidos y usabilidad.</li>
              <li><strong>Cookies de marketing</strong> (solo si las usas): se utilizan para mostrarte contenidos o
                anuncios relacionados con tus intereses en función de tu navegación.</li>
            </ul>
          </section>

          {{-- 3. FINALIDAD --}}
          <section id="finalidad" class="bg-white rounded-2xl shadow p-6">
            <h2 class="text-azul font-semibold text-lg mb-3">3. Finalidad de las cookies</h2>
            <p class="mb-2">Las cookies que utilizamos tienen como finalidad principal:</p>
            <ul class="list-disc ml-5 space-y-1">
              <li>Garantizar el correcto funcionamiento del sitio web y sus formularios.</li>
              <li>Mejorar la experiencia de usuario recordando ciertas preferencias básicas.</li>
              <li>Obtener estadísticas anónimas de uso para mejorar los contenidos y servicios.</li>
              <li>En su caso, mostrar información relevante sobre nuestros servicios (clases, traducciones, interpretación).
              </li>
            </ul>
          </section>

          {{-- 4. TERCEROS --}}
          <section id="terceros" class="bg-white rounded-2xl shadow p-6">
            <h2 class="text-azul font-semibold text-lg mb-3">4. Cookies de terceros</h2>
            <p class="mb-2">
              Algunas cookies pueden ser gestionadas por terceros proveedores, por ejemplo:
            </p>
            <ul class="list-disc ml-5 space-y-1">
              <li><strong>Herramientas de analítica web</strong> (como Google Analytics), que recopilan información
                anónima sobre el uso del sitio.</li>
              <li><strong>Plataformas de vídeo o contenido embebido</strong> (por ejemplo, YouTube, Vimeo), si se
                insertan vídeos en la web.</li>
            </ul>
            <p class="mt-2 text-xs text-gray-600">
              Estos terceros pueden utilizar sus propias cookies bajo sus respectivas políticas de privacidad.
              Te recomendamos consultarlas para obtener más información.
            </p>
          </section>

          {{-- 5. GESTIÓN --}}
          <section id="gestion" class="bg-white rounded-2xl shadow p-6">
            <h2 class="text-azul font-semibold text-lg mb-3">5. Cómo configurar o desactivar las cookies</h2>
            <p class="mb-2">
              Puedes permitir, bloquear o eliminar las cookies instaladas en tu dispositivo mediante la configuración
              de opciones del navegador que utilices. A continuación encontrarás enlaces a la ayuda de los navegadores
              más habituales:
            </p>
            <ul class="list-disc ml-5 space-y-1">
              <li>Google Chrome</li>
              <li>Mozilla Firefox</li>
              <li>Microsoft Edge</li>
              <li>Safari</li>
            </ul>
            <p class="mt-2 text-xs text-gray-600">
              La ruta exacta puede variar según la versión del navegador. Normalmente se encuentra en
              “Configuración” → “Privacidad y seguridad” → “Cookies y otros datos de sitios”.
            </p>
          </section>

          {{-- 6. CONSECUENCIAS --}}
          <section id="consecuencias" class="bg-white rounded-2xl shadow p-6">
            <h2 class="text-azul font-semibold text-lg mb-3">6. ¿Qué ocurre si desactivas las cookies?</h2>
            <p>
              Si decides bloquear algunas cookies, es posible que determinados servicios o funcionalidades del sitio
              web no estén disponibles o no funcionen correctamente (por ejemplo, mantener la sesión iniciada,
              recordar tus preferencias o enviar formularios).
            </p>
          </section>

          {{-- 7. ACTUALIZACIÓN --}}
          <section id="actualizacion" class="bg-white rounded-2xl shadow p-6">
            <h2 class="text-azul font-semibold text-lg mb-3">7. Actualización de la política de cookies</h2>
            <p>
              Esta política de cookies puede actualizarse para adaptarse a cambios legales, técnicos o a la
              configuración de los servicios ofrecidos en la web. Te recomendamos revisarla de forma periódica.
            </p>
          </section>

          {{-- 8. CONTACTO --}}
          <section id="contacto" class="bg-white rounded-2xl shadow p-6">
            <h2 class="text-azul font-semibold text-lg mb-3">8. Contacto</h2>
            <p>
              Si tienes dudas sobre el uso de cookies en este sitio web, puedes escribirnos a
              <a href="mailto:tucorreo@granbretania.com" class="text-azul underline">tucorreo@granbretania.com</a>
              o utilizar nuestro <a href="{{ route('contact.create') }}" class="text-azul underline">formulario de
                contacto</a>.
            </p>
            <p class="mt-3 text-xs text-gray-600">
              Para más información sobre cómo tratamos tus datos personales, puedes consultar nuestra
              <a href="{{ route('privacy') }}" class="text-azul underline">Política de privacidad</a>.
            </p>
          </section>

        </div>
      </div>
    </section>
  @endsection


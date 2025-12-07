@extends('layouts.site')

@section('title', 'Cookies · Gran Bretania')

@section('content')

<section class="container mx-auto px-4 py-10">

  {{-- TÍTULO PRINCIPAL --}}
  <header class="max-w-3xl mb-8">
    <h1 class="text-azul text-3xl md:text-4xl font-semibold mb-3 dark:text-beige2">
      Política de cookies
    </h1>
    <p class="text-gray-700 dark:text-slate-100">
      En <strong>Gran Bretania</strong> utilizamos cookies para mejorar tu experiencia de navegación, analizar el uso
      del sitio y, en su caso, mostrar contenidos adaptados a tus intereses. En esta página te explicamos qué son las
      cookies, qué tipos utilizamos y cómo puedes gestionarlas.
    </p>
  </header>

  {{-- LAYOUT DOS COLUMNAS --}}
  <div class="grid lg:grid-cols-4 gap-10">

    {{-- ÍNDICE LATERAL --}}
    <aside class="lg:col-span-1">
      <div class="bg-beige2 rounded-2xl p-4 text-sm sticky top-24
                  dark:bg-slate-800 dark:text-slate-100">
        <h2 class="text-azul font-semibold mb-3 text-base dark:text-beige2">Contenido</h2>
        <ul class="space-y-2">
          <li><a href="#que-son" class="text-gray-700 hover:text-azul dark:text-slate-100 dark:hover:text-beige2">1. ¿Qué son las cookies?</a></li>
          <li><a href="#tipos" class="text-gray-700 hover:text-azul dark:text-slate-100 dark:hover:text-beige2">2. Tipos de cookies utilizadas</a></li>
          <li><a href="#finalidad" class="text-gray-700 hover:text-azul dark:text-slate-100 dark:hover:text-beige2">3. Finalidad de las cookies</a></li>
          <li><a href="#terceros" class="text-gray-700 hover:text-azul dark:text-slate-100 dark:hover:text-beige2">4. Cookies de terceros</a></li>
          <li><a href="#gestion" class="text-gray-700 hover:text-azul dark:text-slate-100 dark:hover:text-beige2">5. Cómo configurar o desactivar cookies</a></li>
          <li><a href="#consecuencias" class="text-gray-700 hover:text-azul dark:text-slate-100 dark:hover:text-beige2">6. ¿Qué ocurre si las desactivas?</a></li>
          <li><a href="#actualizacion" class="text-gray-700 hover:text-azul dark:text-slate-100 dark:hover:text-beige2">7. Actualización de la política de cookies</a></li>
          <li><a href="#contacto" class="text-gray-700 hover:text-azul dark:text-slate-100 dark:hover:text-beige2">8. Contacto</a></li>
        </ul>
      </div>
    </aside>

    {{-- CONTENIDO PRINCIPAL --}}
    <div class="lg:col-span-3 space-y-8 text-sm md:text-base leading-relaxed text-gray-800 dark:text-slate-100">

      {{-- 1. QUÉ SON --}}
      <section id="que-son" class="bg-white rounded-2xl shadow p-6 dark:bg-slate-900">
        <h2 class="text-azul font-semibold text-lg mb-3 dark:text-beige2">1. ¿Qué son las cookies?</h2>
        <p>
          Las cookies son pequeños archivos de texto que se descargan en tu dispositivo cuando visitas
          determinadas páginas web. Permiten, entre otras cosas, recordar tus preferencias de navegación,
          entender cómo utilizas el sitio y, en algunos casos, reconocer tu dispositivo en visitas posteriores.
        </p>
      </section>

      {{-- 2. TIPOS UTILIZADAS --}}
      <section id="tipos" class="bg-white rounded-2xl shadow p-6 dark:bg-slate-900">
        <h2 class="text-azul font-semibold text-lg mb-3 dark:text-beige2">2. Tipos de cookies utilizadas</h2>
        <p class="mb-2">En <strong>Gran Bretania</strong> podemos utilizar las siguientes categorías de cookies:</p>
        <ul class="list-disc ml-5 space-y-1">
          <li><strong>Cookies técnicas o necesarias</strong>: imprescindibles para el funcionamiento básico del sitio
            (por ejemplo, mantener la sesión iniciada o recordar el contenido de un formulario).</li>
          <li><strong>Cookies de preferencias</strong>: permiten recordar tus ajustes (idioma, región, etc.).</li>
          <li><strong>Cookies estadísticas o de análisis</strong>: nos ayudan a comprender cómo se utiliza la web.</li>
          <li><strong>Cookies de marketing</strong> (solo si las usas): cookies para mostrarte contenidos o anuncios adaptados.</li>
        </ul>
      </section>

      {{-- 3. FINALIDAD --}}
      <section id="finalidad" class="bg-white rounded-2xl shadow p-6 dark:bg-slate-900">
        <h2 class="text-azul font-semibold text-lg mb-3 dark:text-beige2">3. Finalidad de las cookies</h2>
        <p class="mb-2">Las cookies que utilizamos tienen como finalidad principal:</p>
        <ul class="list-disc ml-5 space-y-1">
          <li>Garantizar el correcto funcionamiento del sitio web y sus formularios.</li>
          <li>Mejorar la experiencia de usuario recordando ciertas preferencias básicas.</li>
          <li>Obtener estadísticas anónimas de uso.</li>
          <li>Mostrar información relevante sobre nuestros servicios.</li>
        </ul>
      </section>

      {{-- 4. TERCEROS --}}
      <section id="terceros" class="bg-white rounded-2xl shadow p-6 dark:bg-slate-900">
        <h2 class="text-azul font-semibold text-lg mb-3 dark:text-beige2">4. Cookies de terceros</h2>
        <p class="mb-2">
          Algunas cookies pueden ser gestionadas por terceros proveedores:
        </p>
        <ul class="list-disc ml-5 space-y-1">
          <li><strong>Google Analytics</strong>: recopila datos de navegación anónimos.</li>
          
        </ul>
        <p class="mt-2 text-xs text-gray-600 dark:text-slate-400">
          Estos terceros usan sus propias políticas de cookies.
        </p>
      </section>

      {{-- 5. GESTIÓN --}}
      <section id="gestion" class="bg-white rounded-2xl shadow p-6 dark:bg-slate-900">
        <h2 class="text-azul font-semibold text-lg mb-3 dark:text-beige2">5. Cómo configurar o desactivar las cookies</h2>
        <p class="mb-2">
          Puedes permitir, bloquear o eliminar las cookies desde tu navegador:
        </p>
        <ul class="list-disc ml-5 space-y-1">
          <li>Google Chrome</li>
          <li>Mozilla Firefox</li>
          <li>Microsoft Edge</li>
          <li>Safari</li>
        </ul>
        <p class="mt-2 text-xs text-gray-600 dark:text-slate-400">
          Normalmente está en “Configuración → Privacidad y seguridad”.
        </p>
      </section>

      {{-- 6. CONSECUENCIAS --}}
      <section id="consecuencias" class="bg-white rounded-2xl shadow p-6 dark:bg-slate-900">
        <h2 class="text-azul font-semibold text-lg mb-3 dark:text-beige2">6. ¿Qué ocurre si desactivas las cookies?</h2>
        <p>
          Algunos servicios o funcionalidades pueden dejar de funcionar correctamente (mantener sesión iniciada,
          recordar preferencias, enviar formularios).
        </p>
      </section>

      {{-- 7. ACTUALIZACIÓN --}}
      <section id="actualizacion" class="bg-white rounded-2xl shadow p-6 dark:bg-slate-900">
        <h2 class="text-azul font-semibold text-lg mb-3 dark:text-beige2">7. Actualización de la política de cookies</h2>
        <p>
          Puede actualizarse para adaptarse a cambios legales o técnicos. Te recomendamos revisarla periódicamente.
        </p>
      </section>

      {{-- 8. CONTACTO --}}
      <section id="contacto" class="bg-white rounded-2xl shadow p-6 dark:bg-slate-900">
        <h2 class="text-azul font-semibold text-lg mb-3 dark:text-beige2">8. Contacto</h2>
        <p>
          Escríbenos a
          <a href="mailto:tucorreo@granbretania.com" class="text-azul underline dark:text-beige2">tucorreo@granbretania.com</a>
          o usa el
          <a href="{{ route('contact.create') }}" class="text-azul underline dark:text-beige2">formulario de contacto</a>.
        </p>

        <p class="mt-3 text-xs text-gray-600 dark:text-slate-400">
          Más información sobre protección de datos en la
          <a href="{{ route('privacy') }}" class="text-azul underline dark:text-beige2">Política de privacidad</a>.
        </p>
      </section>

    </div>
  </div>
</section>

@endsection


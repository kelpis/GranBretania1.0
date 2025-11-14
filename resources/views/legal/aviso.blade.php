@extends('layouts.site')

@section('title', 'Aviso legal · Gran Bretania')

@section('content')
  
<section class="container mx-auto px-4 py-10">

    {{-- MIGAS DE PAN --}}
    <nav class="text-sm text-gray-600 mb-6" aria-label="Breadcrumb">
        <ol class="list-none p-0 inline-flex">
            <li class="flex items-center">
                <a href="{{ route('home') }}" class="text-azul hover:text-rojo">Inicio</a>
                <svg class="w-4 h-4 mx-2 text-gray-400" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </li>
            <li class="flex items-center text-gray-500" aria-current="page">Aviso legal</li>
        </ol>
    </nav>

    {{-- TÍTULO --}}
    <header class="max-w-3xl mb-8">
        <h1 class="text-azul text-3xl md:text-4xl font-semibold mb-3">Aviso legal</h1>
        <p class="text-gray-700">
            Este Aviso Legal regula el acceso y uso del sitio web <strong>Gran Bretania</strong> (www.tudominio.com)
            que su titular pone a disposición de los usuarios de Internet.
        </p>
    </header>

    <div class="grid lg:grid-cols-4 gap-10">
        
        {{-- ÍNDICE --}}
        <aside class="lg:col-span-1">
            <div class="bg-beige2 rounded-2xl p-4 text-sm sticky top-24">
                <h2 class="text-azul font-semibold mb-3 text-base">Contenido</h2>
                <ul class="space-y-2">
                    <li><a href="#titular" class="hover:text-azul">1. Titular de la web</a></li>
                    <li><a href="#objeto" class="hover:text-azul">2. Objeto y ámbito de aplicación</a></li>
                    <li><a href="#uso" class="hover:text-azul">3. Condiciones de uso</a></li>
                    <li><a href="#propiedad" class="hover:text-azul">4. Propiedad intelectual e industrial</a></li>
                    <li><a href="#responsabilidad" class="hover:text-azul">5. Responsabilidad del titular</a></li>
                    <li><a href="#enlaces" class="hover:text-azul">6. Enlaces externos</a></li>
                    <li><a href="#ley" class="hover:text-azul">7. Legislación aplicable</a></li>
                    <li><a href="#contacto" class="hover:text-azul">8. Contacto</a></li>
                </ul>
            </div>
        </aside>

        {{-- CONTENIDO --}}
        <div class="lg:col-span-3 space-y-8 text-gray-800 text-sm md:text-base leading-relaxed">

            {{-- 1. TITULAR --}}
            <section id="titular" class="bg-white rounded-2xl shadow p-6">
                <h2 class="text-azul font-semibold text-lg mb-3">1. Titular de la web</h2>
                <p>
                    Titular: <strong>Gran Bretania</strong> — (Nombre completo o razón social).<br>
                    NIF/CIF: (indicar).<br>
                    Domicilio: (dirección completa).<br>
                    Email: <a href="mailto:tucorreo@granbretania.com" class="text-azul underline">tucorreo@granbretania.com</a>.<br>
                    Actividad: Formación online en inglés, traducciones e interpretación.
                </p>
            </section>

            {{-- 2. OBJETO --}}
            <section id="objeto" class="bg-white rounded-2xl shadow p-6">
                <h2 class="text-azul font-semibold text-lg mb-3">2. Objeto y ámbito de aplicación</h2>
                <p>
                    El acceso y uso de este sitio web te atribuye la condición de usuario y supone la aceptación plena de este Aviso Legal.
                    Las presentes condiciones regulan el acceso, la navegación y el uso del sitio.
                </p>
            </section>

            {{-- 3. USO --}}
            <section id="uso" class="bg-white rounded-2xl shadow p-6">
                <h2 class="text-azul font-semibold text-lg mb-3">3. Condiciones de uso</h2>
                <ul class="list-disc ml-5 space-y-1">
                    <li>El usuario se compromete a hacer un uso adecuado del sitio y de los contenidos.</li>
                    <li>No se permite usar la web para actividades ilícitas o que puedan dañar a terceros.</li>
                    <li>El titular puede modificar, suspender o eliminar contenidos sin previo aviso.</li>
                </ul>
            </section>

            {{-- 4. PROPIEDAD INTELECTUAL --}}
            <section id="propiedad" class="bg-white rounded-2xl shadow p-6">
                <h2 class="text-azul font-semibold text-lg mb-3">4. Propiedad intelectual e industrial</h2>
                <p>
                    Todos los contenidos del sitio web (textos, imágenes, logotipos, diseño, etc.)
                    son propiedad de <strong>Gran Bretania</strong> o de terceros con licencia.
                    Queda prohibida su reproducción, distribución o modificación sin autorización expresa.
                </p>
            </section>

            {{-- 5. RESPONSABILIDAD --}}
            <section id="responsabilidad" class="bg-white rounded-2xl shadow p-6">
                <h2 class="text-azul font-semibold text-lg mb-3">5. Responsabilidad del titular</h2>
                <ul class="list-disc ml-5 space-y-1">
                    <li>El titular no garantiza la inexistencia de errores o interrupciones, aunque trabajamos para evitarlos.</li>
                    <li>No se responsabiliza del mal uso que los usuarios puedan hacer del contenido.</li>
                    <li>No se responsabiliza del contenido de sitios externos enlazados desde esta web.</li>
                </ul>
            </section>

            {{-- 6. ENLACES --}}
            <section id="enlaces" class="bg-white rounded-2xl shadow p-6">
                <h2 class="text-azul font-semibold text-lg mb-3">6. Enlaces externos</h2>
                <p>
                    Este sitio puede contener enlaces a páginas de terceros.
                    El titular no se hace responsable de los contenidos o políticas de dichos sitios.
                </p>
            </section>

            {{-- 7. LEY --}}
            <section id="ley" class="bg-white rounded-2xl shadow p-6">
                <h2 class="text-azul font-semibold text-lg mb-3">7. Legislación aplicable</h2>
                <p>
                    Este Aviso Legal se rige por la legislación española.
                    Para cualquier conflicto, serán competentes los Juzgados y Tribunales de <strong>(tu ciudad)</strong>.
                </p>
            </section>

            {{-- 8. CONTACTO --}}
            <section id="contacto" class="bg-white rounded-2xl shadow p-6">
                <h2 class="text-azul font-semibold text-lg mb-3">8. Contacto</h2>
                <p>
                    Para cualquier consulta relacionada con este Aviso Legal:
                    <a href="mailto:tucorreo@granbretania.com" class="text-azul underline">tucorreo@granbretania.com</a>
                    o utiliza el <a href="{{ route('contact.create') }}" class="text-azul underline">formulario de contacto</a>.
                </p>
            </section>

        </div>
    </div>
</section>


@endsection

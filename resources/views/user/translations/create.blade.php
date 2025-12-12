{{--
  Vista: user/translations/create.blade.php
  Formulario para solicitar una traducción; adjuntar archivo y datos de contacto.
 --}}

@extends('layouts.site')

@section('title', 'Solicitud de traducción · Gran Bretania')

@section('header')
  @include('layouts.navigation')
@endsection

@section('content')

  <section class="bg-beige2 py-12 dark:bg-slate-950">
    <div class="container mx-auto px-4">

      
      <div class="max-w-2xl mx-auto text-center mb-10">
        <h2 class="text-azul text-3xl font-semibold mb-2 dark:text-beige2">Solicitud de traducción</h2>
        <p class="text-gray-700 text-base leading-relaxed mx-auto max-w-xl dark:text-slate-100">
          Rellena el formulario y adjunta el archivo a traducir. Te contactaremos con presupuesto y plazo.
        </p>
      </div>

      
      <div class="grid lg:grid-cols-3 gap-8 max-w-5xl mx-auto items-start">

        
        <div class="lg:col-span-2 bg-azul text-beige2 rounded-2xl shadow-lg p-8">

          @if (session('ok'))
            <div class="mb-4 bg-ok/10 text-white border border-ok/40 p-3 rounded text-sm dark:bg-emerald-950 dark:text-emerald-100 dark:border-emerald-500/60">
              {{ session('ok') }}
            </div>
          @endif

          @if ($errors->any())
            <div class="mb-4 bg-red-50 text-red-700 p-3 rounded text-sm dark:bg-red-900 dark:text-red-100 dark:border dark:border-red-500/70">
              <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $e)
                  <li>{{ $e }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          {{-- Formulario principal: envío multipart a translation.store (calculo de presupuesto y reCAPTCHA) --}}
          <form id="translation-form" method="POST" action="{{ route('translation.store') }}"
            enctype="multipart/form-data" class="space-y-5" data-grecaptcha="v3" data-recaptcha-action="translation">
            @csrf

            {{-- Campos de contacto: nombre y email (requeridos) --}}
            <div class="grid md:grid-cols-2 gap-4">
              <div>
                <x-input-label class="text-beige2">Nombre</x-input-label>
                <input name="name"
                  class="block mt-1 w-full bg-white text-azul rounded p-2 dark:bg-slate-100"
                  value="{{ old('name', auth()->user()->name ?? '') }}" required>
              </div>

              <div>
                <x-input-label class="text-beige2">Email</x-input-label>
                <input type="email" name="email"
                  class="block mt-1 w-full bg-white text-azul rounded p-2 dark:bg-slate-100"
                  value="{{ old('email', auth()->user()->email ?? '') }}" required>
              </div>
            </div>

            {{-- Selección de idiomas: origen y destino (requeridos) --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <x-input-label class="text-beige2">Idioma origen</x-input-label>
                <select name="source_lang"
                  class="block mt-1 w-full bg-white text-azul rounded p-2 dark:bg-slate-100"
                  required>
                  <option value="">— Selecciona idioma —</option>
                  <option value="en" @selected(old('source_lang') === 'en')>Inglés</option>
                  <option value="es" @selected(old('source_lang') === 'es')>Español</option>
                  <option value="fr" @selected(old('source_lang') === 'fr')>Francés</option>
                </select>
              </div>
              <div>
                <x-input-label class="text-beige2">Idioma destino</x-input-label>
                <select name="target_lang"
                  class="block mt-1 w-full bg-white text-azul rounded p-2 dark:bg-slate-100"
                  required>
                  <option value="">— Selecciona idioma —</option>
                  <option value="en" @selected(old('target_lang') === 'en')>Inglés</option>
                  <option value="es" @selected(old('target_lang') === 'es')>Español</option>
                  <option value="fr" @selected(old('target_lang') === 'fr')>Francés</option>
                </select>
              </div>
            </div>

            {{-- Archivo a traducir: upload con validación de tipo y tamaño --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-start">
              <div class="md:col-span-2">
                <x-input-label class="text-beige2">Archivo</x-input-label>
                <input type="file" name="file"
                  class="block mt-1 w-full bg-white text-azul rounded p-2 dark:bg-slate-100"
                  accept=".pdf,.doc,.docx,.odt,.txt,.rtf" required>
                <p class="text-xs text-white/80 mt-1">
                  Formatos permitidos: PDF, DOC, DOCX, ODT, TXT, RTF. Tamaño máximo: 10MB.
                </p>
              </div>
              <div class="md:col-span-1">
                <x-input-label class="text-beige2">Urgencia</x-input-label>
                <select name="urgency"
                  class="block mt-1 w-full bg-white text-azul rounded p-2 dark:bg-slate-100">
                  <option value="normal" {{ old('urgency') === 'normal' ? 'selected' : '' }}>Normal</option>
                  <option value="alta" {{ old('urgency') === 'alta' ? 'selected' : '' }}>Alta</option>
                </select>
              </div>
            </div>

            {{-- Comentarios adicionales: textarea opcional --}}
            <div>
              <x-input-label class="text-beige2">Observaciones (opcional)</x-input-label>
              <textarea name="comments" rows="4"
                class="block mt-1 w-full bg-white text-azul rounded p-2 dark:bg-slate-100"
                placeholder="Contexto, público objetivo, preferencias de estilo…">{{ old('comments') }}</textarea>
            </div>

            
            <div>
              <label class="inline-flex items-center text-sm text-beige2">
                <input type="checkbox" name="gdpr" value="1" required
                  class="rounded border-white/30 text-azul">
                <span class="ml-2">
                  He leído y acepto la
                  <a href="{{ route('privacy') }}" class="underline">política de protección de datos</a>.
                </span>
              </label>
            </div>

            <div class="flex items-center justify-center mt-4">
              <button id="tr-submit" class="btn-secondary hover:!bg-beige hover:!text-negro">
                Enviar solicitud
              </button>
            </div>

          </form>
        </div>

        {{-- Aside informativo: explica el proceso y tipos de textos --}}
        <aside class="bg-white rounded-2xl shadow p-6 text-sm text-gray-700 dark:bg-slate-900 dark:text-slate-100 dark:border dark:border-slate-700">
          <h3 class="text-azul text-lg font-semibold mb-3 dark:text-beige2">¿Qué ocurre después?</h3>
          <ol class="list-decimal pl-5 space-y-1 mb-4">
            <li>Revisamos tu archivo y el par de idiomas.</li>
            <li>Calculamos el volumen de texto y la complejidad.</li>
            <li>Te enviamos un presupuesto con plazo aproximado.</li>
          </ol>

          <h4 class="text-azul font-semibold mb-2 dark:text-beige2">Tipos de textos habituales</h4>
          <ul class="list-disc pl-5 space-y-1 mb-4">
            <li>Informes y documentación interna.</li>
            <li>Presentaciones y material comercial.</li>
            <li>Currículums, cartas de motivación y perfiles.</li>
          </ul>

          <p class="text-xs text-gray-500 dark:text-slate-300">
            Si tienes dudas sobre el formato o el tipo de texto, indícalo en el campo de observaciones
            y te orientaremos en la respuesta.
          </p>
        </aside>

      </div>
    </div>
  </section>

  @vite(['resources/js/translation.js'])

@endsection

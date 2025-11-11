@extends('layouts.site')

@section('title', 'Contacto· Gran Bretania')

@section('content')
<section id="contacto" class="py-16">
  <div class="container mx-auto px-4 grid md:grid-cols-2 gap-8 items-stretch min-h-[560px]">

    {{-- Columna izquierda: imagen + texto arriba --}}
    <div class="relative rounded-xl overflow-hidden h-full">
      <img
        src="{{ asset('images/libros-ingleses.jpg') }}"
        alt="Comunicación y asesoramiento en inglés y traducción"
        class="absolute inset-0 w-full h-full object-cover"
      />
      <div class="absolute inset-0 bg-gradient-to-b from-azul/70 via-azul/40 to-azul/20"></div>

      <div class="relative p-6 md:p-8">
        <h2 class="text-white text-3xl md:text-4xl font-semibold">Contacto</h2>
        <p class="mt-4 max-w-xl text-white/90 text-lg md:text-xl leading-relaxed">
          ¿Tienes alguna consulta antes de comenzar?
          Escríbeme y estaré encantada de resolver tus dudas sobre clases, tarifas o servicios de traducción.
        </p>
      </div>
    </div>

    {{-- Columna derecha: formulario (igual altura) --}}
    <div class="h-full">
      <div class="bg-azul rounded-xl shadow p-6 md:p-8 h-full flex flex-col">
        @if (session('ok'))
          <div class="mb-4 bg-green-100 text-green-700 p-3 rounded">{{ session('ok') }}</div>
        @endif

        @if ($errors->any())
          <div class="mb-4 bg-red-100 text-red-700 p-3 rounded">
            <ul class="list-disc ml-5">
              @foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
          </div>
        @endif

  <div class="bg-azul overflow-hidden shadow-sm sm:rounded-lg pt-4 pb-6 px-6 flex-1 text-white">
          <form method="POST" action="{{ route('contact.store') }}"
                class="space-y-4 h-full flex flex-col"
                data-grecaptcha="v3" data-recaptcha-action="contact">
            @csrf

            <div>
              <label class="block mb-1 font-medium text-beige2" for="name">Nombre</label>
              <input id="name" name="name" class="w-full border rounded p-2 bg-white text-azul" value="{{ old('name') }}" required>
            </div>

            <div>
              <label class="block mb-1 font-medium text-beige2" for="email">Email</label>
              <input id="email" type="email" name="email" class="w-full border rounded p-2 bg-white text-azul" value="{{ old('email') }}" required>
            </div>

            <div>
              <label class="block mb-1 font-medium text-beige2" for="subject">Asunto (opcional)</label>
              <input id="subject" name="subject" class="w-full border rounded p-2 bg-white text-azul"
                     value="{{ old('subject', request('subject')) }}">
            </div>

            <div>
              <label class="block mb-1 font-medium text-beige2" for="message">Mensaje</label>
              <textarea id="message" name="message" rows="5" class="w-full border rounded p-2 bg-white text-azul"
                        @if(request('subject')) placeholder="Cuéntame tu nivel, objetivos y horarios preferidos para agendar la clase de prueba." @endif
                        required>{{ old('message') }}</textarea>
            </div>

            <div class="flex items-start gap-3">
              <label class="inline-flex items-center text-sm">
                <input type="checkbox" name="gdpr" value="1" required class="border">
                <span class="ml-2">He leído y acepto la
                  <a href="{{ route('privacy') }}" class="text-white underline">política de protección de datos</a>.
                </span>
              </label>
            </div>

            <div class="mt-4">
              <button class="btn-secondary">Enviar</button>
            </div>
          </form>
        </div>
      </div>
    </div>

  </div>
</section>

@endsection


{{--
  Vistas de `bookings` → success.blade.php
  Propósito: mostrar confirmación tras el envío/compra de una reserva.
  Notas: vista estática; evita lógica JS. Mantener enlaces seguros hacia `dashboard`.
--}}

@extends('layouts.site')

@section('Reserva enviada', 'Reserva enviada')

@section('content')
<section class="py-24">
   <div class="py-10 max-w-3xl mx-auto px-4">
        <div class="bg-white border-l-4 border-green-500 shadow-lg rounded-xl p-8 dark:bg-slate-800/90">

            {{-- Encabezado: mensaje de éxito --}}
            <h3 class="text-xl font-semibold text-azul mb-3 dark:text-white">
                ¡Reserva enviada!
            </h3>

            {{-- Mensaje al usuario: correo con detalles y enlace de la clase --}}
            <p class="text-gray-700 leading-relaxed dark:text-white">
                Hemos recibido tu pago.
                Pronto recibirás un correo con los detalles y el enlace de la clase.
            </p>

            {{-- Botón: volver al panel --}}
            <div class="mt-8">
                <a href="{{ route('dashboard') }}"
                   class="inline-flex items-center gap-2 px-6 py-3 
                          bg-rojo text-white font-medium rounded-lg
                          shadow hover:bg-red-700 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" 
                         class="w-5 h-5" fill="none" viewBox="0 0 24 24" 
                         stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" 
                              d="M15 19l-7-7 7-7" />
                    </svg>
                    Volver al panel
                </a>
            </div>

        </div>
    </div>
</section>
@endsection

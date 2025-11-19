
@extends('layouts.site')

@section('Reserva enviada', 'Reserva enviada')

@section('content')
<section class="py-24">
    <div class="p-6 bg-green-50 border border-green-200 rounded dark:bg-slate-800/90">
    <h2 class="text-xl font-semibold mb-2 dark:text-white">¡Reserva enviada!</h2>
    <p class="text-sm text-green-800 dark:text-white">
      Hemos recibido tu pago. 
      Pronto recibirás un correo con los detalles y el enlace de la clase.
    </p>
    <div class="mt-4">
      <a href="{{ route('dashboard') }}" class="inline-block px-4 py-2 bg-blue-600 text-white font-medium rounded hover:bg-blue-700">
        Volver al panel
      </a>
    </div>
  </div>
</section>
@endsection

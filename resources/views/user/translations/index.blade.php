{{--
    Vista: user/translations/index.blade.php
    Propósito: listado de solicitudes de traducción del usuario (móvil: tarjetas, desktop: tabla).
    Notas: muestra estado, enlace de descarga cuando esté entregada y mensajes flash (Stripe, sesión).
--}}

@extends('layouts.site')

@section('title', 'Mis traducciones · Gran Bretania')

@section('header')
    @include('layouts.navigation')
@endsection

@section('content')

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-4">

            {{-- Mensajes flash --}}
            @if(session('ok'))
                <div class="bg-green-100 text-green-800 p-3 rounded">{{ session('ok') }}</div>
            @endif

            {{-- Mensaje si venimos de Stripe con parámetro t_paid --}}
            @if(request()->has('t_paid'))
                <div class="bg-blue-50 text-blue-800 p-3 rounded text-sm">
                    Hemos recibido la confirmación de tu pago de traducción. En breve comenzaremos a trabajar en tu encargo.
                </div>
            @endif

            {{-- Si no hay solicitudes, mostrar mensaje sencillo --}}
            @if($items->isEmpty())
                <div class="bg-white p-6 shadow sm:rounded-lg">
                    No tienes solicitudes de traducción.
                </div>
            @else
                <div class="rounded-2xl shadow-xl overflow-hidden border border-beige bg-beige2">

                    {{-- CABECERA: título de la lista y estilos de cabecera --}}
                    <div class="bg-azul text-beige2 px-6 py-4">
                        <h2 class="font-semibold text-xl leading-tight">Mis traducciones</h2>
                    </div>

                    @php
                        $statusLabels = [
                            'pending'   => 'Pendiente de revisión',
                            'quoted'    => 'Presupuesto enviado',
                            'paid'      => 'Pago recibido',
                            'delivered' => 'Entregada',
                        ];
                    @endphp

                    {{-- Movil: tarjetas --}}
                    <div class="md:hidden px-4 py-4 space-y-3">

                        @foreach($items as $t)
                            @php
                                $status = $t->status ?? 'pending';
                                $label  = $statusLabels[$status] ?? ucfirst($status);
                            @endphp

                            <div class="bg-white p-4 rounded-lg shadow-sm border dark:bg-slate-900 dark:text-slate-100 dark:border-slate-700">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <div class="text-xs text-gray-500">Enviado</div>
                                        <div class="font-medium text-sm text-negro dark:text-white">
                                            {{ \Carbon\Carbon::parse($t->created_at)->format('d/m/Y H:i') }}
                                        </div>
                                    </div>

                                    <div class="text-right">
                                        <div class="text-xs text-gray-500">Idiomas</div>
                                        <div class="font-medium text-sm text-negro dark:text-white">
                                            {{ strtoupper($t->source_lang) }} → {{ strtoupper($t->target_lang) }}
                                        </div>
                                        <div class="mt-1 text-xs text-gray-500">Urgencia</div>
                                        <div class="text-sm text-negro dark:text-white">{{ ucfirst($t->urgency) }}</div>
                                    </div>
                                </div>

                                @if($t->comments)
                                    <div class="mt-3 text-sm text-negro dark:text-white">
                                        {{ \Illuminate\Support\Str::limit($t->comments, 140) }}
                                    </div>
                                @endif

                                {{-- Estado --}}
                                <div class="mt-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($status === 'delivered')
                                            bg-ok text-white
                                        @elseif($status === 'paid')
                                            bg-azul text-beige2
                                        @elseif($status === 'quoted')
                                            bg-amber-200 text-amber-900
                                        @else
                                            bg-gray-200 text-gray-800
                                        @endif
                                    ">
                                        {{ $label }}
                                    </span>
                                </div>

                                {{-- Archivo / acciones --}}
                                <div class="mt-3 text-sm">
                                    @php
                                        $hasOutput = $t->output_file_path
                                            && \Illuminate\Support\Facades\Storage::disk('local')->exists($t->output_file_path);
                                    @endphp

                                    @if($status === 'delivered' && $hasOutput)
                                        <a href="{{ route('user.translations.download', $t->id) }}"
                                           class="inline-block px-3 py-1 rounded-full bg-azul text-beige2 text-xs font-medium hover:bg-rojo transition">
                                            Descargar traducción
                                        </a>
                                    @elseif($status === 'quoted')
                                        <span class="text-gray-700">
                                            Presupuesto enviado. Revisa tu email para completar el pago.
                                        </span>
                                    @elseif($status === 'paid')
                                        <span class="text-gray-700">
                                            Pago recibido. Tu traducción está en proceso.
                                        </span>
                                    @else
                                        <span class="text-gray-500">
                                            Tu solicitud está pendiente de revisión.
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- DESKTOP: tabla --}}
                    <div class="hidden md:block w-full overflow-x-auto">
                        <table class="min-w-[640px] w-full table-auto text-sm text-azul dark:text-beige2">
                            <thead class="bg-beige/80 text-azul uppercase text-xs tracking-wider dark:bg-slate-800/80 dark:text-beige2 dark:border-slate-700">
                                <tr>
                                    <th class="py-3 px-4 text-left">Enviado</th>
                                    <th class="py-3 px-4 text-left">Idiomas</th>
                                    <th class="py-3 px-4 text-left">Urgencia</th>
                                    <th class="py-3 px-4 text-left">Comentarios</th>
                                    <th class="py-3 px-4 text-left">Estado</th>
                                    <th class="py-3 px-4 text-left">Archivo</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($items as $t)
                                    @php
                                        $status = $t->status ?? 'pending';
                                        $label  = $statusLabels[$status] ?? ucfirst($status);
                                        $hasOutput = $t->output_file_path
                                            && \Illuminate\Support\Facades\Storage::disk('local')->exists($t->output_file_path);
                                    @endphp

                                    <tr class="odd:bg-beige2 even:bg-white hover:bg-azul/5 transition dark:odd:bg-slate-800 dark:even:bg-slate-900 dark:hover:bg-slate-700">
                                        <td class="py-3 px-4 font-medium text-negro dark:text-white">
                                            {{ \Carbon\Carbon::parse($t->created_at)->format('d/m/Y H:i') }}
                                        </td>

                                        <td class="py-3 px-4 text-negro dark:text-white">
                                            {{ strtoupper($t->source_lang) }} → {{ strtoupper($t->target_lang) }}
                                        </td>

                                        <td class="py-3 px-4 text-negro dark:text-white">
                                            {{ ucfirst($t->urgency) }}
                                        </td>

                                        <td class="py-3 px-4 text-negro dark:text-white">
                                            {{ \Illuminate\Support\Str::limit($t->comments, 80) }}
                                        </td>

                                        {{-- Estado --}}
                                        <td class="py-3 px-4 text-negro dark:text-white">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($status === 'delivered')
                                                    bg-ok text-white
                                                @elseif($status === 'paid')
                                                    bg-azul text-beige2
                                                @elseif($status === 'quoted')
                                                    bg-amber-200 text-amber-900
                                                @else
                                                    bg-gray-200 text-gray-800
                                                @endif
                                            ">
                                                {{ $label }}
                                            </span>
                                        </td>

                                        {{-- Archivo --}}
                                        <td class="py-3 px-4 text-negro dark:text-white">
                                            @if($status === 'delivered' && $hasOutput)
                                                <a href="{{ route('user.translations.download', $t->id) }}"
                                                   class="px-3 py-1 rounded-full bg-azul text-beige2 text-xs font-medium hover:bg-rojo transition">
                                                    Descargar traducción
                                                </a>
                                            @elseif($status === 'quoted')
                                                <span class="text-xs text-gray-700">
                                                    Presupuesto enviado
                                                </span>
                                            @elseif($status === 'paid')
                                                <span class="text-xs text-gray-700">
                                                    En proceso
                                                </span>
                                            @else
                                                <span class="text-xs text-gray-500">
                                                    Pendiente
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            @endif

        </div>
    </div>

@endsection

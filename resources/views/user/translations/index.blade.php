@extends('layouts.site')

@section('title', 'Mis traducciones · Gran Bretania')

@section('header')
    @include('layouts.navigation')
@endsection

@section('content')

    <div class="py-6">

        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if(session('ok'))
                <div class="bg-green-100 text-green-800 p-3 rounded">{{ session('ok') }}</div>
            @endif

            @if($items->isEmpty())
                <div class="bg-white p-6 shadow sm:rounded-lg">No tienes solicitudes de traducción.</div>
            @else
                <div class="rounded-2xl shadow-xl overflow-hidden border border-beige">

                    {{-- CABECERA AZUL CORPORATIVA --}}
                    <div class="bg-azul text-beige2 px-6 py-4">
                        <h2 class="font-semibold text-xl text-beige2-800 leading-tight">Mis traducciones</h2>
                    </div>

                    <!-- Mobile: tarjetas (ocultas en md+) -->
                    <div class="md:hidden px-4 py-4 space-y-3">
                        @foreach($items as $t)
                            <div class="bg-white p-4 rounded-lg shadow-sm border">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <div class="text-xs text-gray-500">Enviado</div>
                                        <div class="font-medium text-sm text-negro">{{ \Carbon\Carbon::parse($t->created_at)->format('d/m/Y H:i') }}</div>
                                    </div>

                                    <div class="text-right">
                                        <div class="text-xs text-gray-500">Idiomas</div>
                                        <div class="font-medium text-sm text-negro">{{ strtoupper($t->source_lang) }} → {{ strtoupper($t->target_lang) }}</div>
                                        <div class="mt-1 text-xs text-gray-500">Urgencia</div>
                                        <div class="text-sm text-negro">{{ ucfirst($t->urgency) }}</div>
                                    </div>
                                </div>

                                @if($t->comments)
                                    <div class="mt-3 text-sm text-negro">{{ \Illuminate\Support\Str::limit($t->comments, 140) }}</div>
                                @endif

                                <div class="mt-3">
                                    @if($t->file_path && \Illuminate\Support\Facades\Storage::disk('local')->exists($t->file_path))
                                        <a href="{{ route('user.translations.download', $t->id) }}" class="text-blue-600 text-sm">Descargar</a>
                                    @else
                                        <span class="text-sm text-gray-500">—</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="hidden md:block w-full overflow-x-auto">
                        <table class="min-w-[640px] w-full table-fixed text-sm">
                        <colgroup>
                            <col style="width:20%"> <!-- Enviado -->
                            <col style="width:25%"> <!-- Idiomas -->
                            <col style="width:15%"> <!-- Urgencia -->
                            <col style="width:20%"> <!-- Comentarios -->
                            <col style="width:20%"> <!-- Archivo -->
                        </colgroup>
                        <thead class="bg-beige/80 text-azul uppercase text-xs tracking-wider">
                            <tr>
                                <th class="py-3 px-4 text-left">Enviado</th>
                                <th class="py-3 px-4 text-left">Idiomas</th>
                                <th class="py-3 px-4 text-left">Urgencia</th>
                                <th class="py-3 px-4 text-left">Comentarios</th>
                                <th class="py-3 px-4 text-left">Archivo</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($items as $t)
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

                                    <td class="py-3 px-4 text-negro dark:text-white">
                                        @if($t->file_path && \Illuminate\Support\Facades\Storage::disk('local')->exists($t->file_path))
                                            <a href="{{ route('user.translations.download', $t->id) }}" class="text-blue-600">Descargar</a>
                                        @else
                                            —
                                        @endif
                                    </td>
                                </tr>

                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

@endsection

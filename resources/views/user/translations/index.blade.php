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

                    <table class="w-full table-fixed text-sm">
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
                                <tr class="odd:bg-beige2 even:bg-white hover:bg-azul/5 transition">

                                    <td class="py-3 px-4 font-medium text-negro">
                                        {{ \Carbon\Carbon::parse($t->created_at)->format('d/m/Y H:i') }}
                                    </td>

                                    <td class="py-3 px-4 text-negro">
                                        {{ strtoupper($t->source_lang) }} → {{ strtoupper($t->target_lang) }}
                                    </td>

                                    <td class="py-3 px-4 text-negro">
                                        {{ ucfirst($t->urgency) }}
                                    </td>

                                    <td class="py-3 px-4 text-negro">
                                        {{ \Illuminate\Support\Str::limit($t->comments, 80) }}
                                    </td>

                                    <td class="py-3 px-4 text-negro">
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

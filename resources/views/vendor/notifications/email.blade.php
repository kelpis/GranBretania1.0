<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $subject ?? config('app.name') }}</title>

    <style>
        body {
            background-color: #f3e8d0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            padding: 30px;
            margin: 0;
            text-align: center;
        }

        .wrapper {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 14px;
            overflow: hidden;
            border: 1px solid #e4e4e4;
        }

        .content {
            padding: 40px 30px;
            background: #eef3f8;
            text-align: center;
        }

        .content p,
        .content h2 {
            text-align: center !important;
            margin: 0 0 16px 0;
            line-height: 1.6;
            color: #111;
        }

        .button {
            display: inline-block;
            background: #D51C3B;
            padding: 14px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            font-size: 16px;
            color: #ffffff !important;
            margin-top: 20px;
            text-align: center;
        }

        .footer {
            padding: 18px;
            background: #fafafa;
            color: #777;
            font-size: 12px;
            text-align: center;
        }
    </style>
</head>

<body>
<div class="wrapper">

    {{-- HEADER --}}
    <div class="header" style="
        background:#011A6B;
        padding: 30px 30px;
        text-align: center;
    ">
        <h1 style="
            margin: 0 auto;
            font-size: 26px;
            font-weight: 700;
            color: #ffffff !important;
            display: inline-block;
            padding: 0 10px;
        ">
            Gran Bretania
        </h1>
    </div>

    {{-- CONTENIDO --}}
    <div class="content">
        @php
            // ¿Viene de ->markdown() con vista propia?
            $usingSlot = isset($slot) && trim((string) $slot) !== '';
        @endphp

        @if ($usingSlot)
            {{-- Caso 1: notificaciones con ->markdown() --}}
            {!! Illuminate\Mail\Markdown::parse($slot) !!}
        @else
            {{-- Caso 2: MailMessage con greeting/line/action --}}

            {{-- Saludo --}}
            @if (! empty($greeting))
                <h2 style="margin-bottom: 22px;">{{ $greeting }}</h2>
            @endif

            {{-- Intro --}}
            @if (! empty($introLines))
                @foreach ($introLines as $line)
                    <p>{{ $line }}</p>
                @endforeach
            @endif

            {{-- Botón --}}
            @isset($actionText)
                <div style="margin: 30px 0;">
                    <a href="{{ $actionUrl }}" class="button">
                        {{ $actionText }}
                    </a>
                </div>
            @endisset

            {{-- Outro --}}
            @if (! empty($outroLines))
                @foreach ($outroLines as $line)
                    <p>{{ $line }}</p>
                @endforeach
            @endif

            {{-- Despedida --}}
            <p style="margin-top: 30px;">
                @if (! empty($salutation))
                    {!! nl2br(e($salutation)) !!}
                @else
                    Un saludo,<br>{{ config('app.name') }}
                @endif
            </p>
        @endif
    </div>

    {{-- FOOTER --}}
    @isset($actionText)
        <div class="footer" style="font-size: 11px;">
            Si tienes problemas para hacer clic en el botón
            "{{ $actionText }}", copia y pega esta URL en tu navegador:<br>
            <span style="word-break: break-all;">{{ $displayableActionUrl ?? $actionUrl }}</span>
        </div>
    @else
        <div class="footer">
            Este mensaje se ha enviado automáticamente por {{ config('app.name') }}.
        </div>
    @endisset

</div>
</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="x-apple-disable-message-reformatting">
    <title>{{ $subject ?? config('app.name') }}</title>

    <style>
        body {
            background-color: #f3e8d0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            padding: 30px;
            margin: 0;
            text-align: center;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
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
            margin: 16px 0 16px 0;
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
            padding: 26px 18px;
            background: #011A6B;
            color: white;
            font-size: 12px;
            text-align: center;
            line-height: 1.4;
        }

        /* Responsive tweaks for narrow clients */
        @media only screen and (max-width: 480px) {
            .wrapper { padding: 0 10px; }
            .content { padding: 20px 16px; }
            .button { padding: 12px 18px; font-size: 15px; }
        }
    </style>
</head>

<body>
<div class="wrapper">

    {{-- Preheader text (hidden but visible in inbox preview) --}}
    <span style="display:none!important;visibility:hidden;mso-hide:all;opacity:0;color:transparent;height:0;width:0;overflow:hidden;">{{ $preheader ?? (strip_tags(implode(' ', $introLines ?? [])) ?: config('app.name')) }}</span>

    {{-- HEADER: título con subtítulo  --}}
    <div class="header" style="background: linear-gradient(90deg,#01256b 0%,#011a6b 100%); padding: 20px 18px; text-align: center; border-top-left-radius:14px; border-top-right-radius:14px;">
        <div style="max-width:560px; margin:0 auto;">
            <h1 style="margin:0; font-size:22px; font-weight:700; color:#ffffff;">{{ config('app.name') }}</h1>
            <h2 style="margin:6px 0 0 0; font-size:13px; font-weight:600; color:#D51C3B; text-align:center;">Enseñanza de inglés y traducciones</h2>
        </div>
    </div>

    
    <div class="content">
        @php
            
            $usingSlot = isset($slot) && trim((string) $slot) !== '';
        @endphp

        @if ($usingSlot)
            
            {!! Illuminate\Mail\Markdown::parse($slot) !!}
        @else
            

            
            @if (! empty($greeting))
                <h2 style="margin-bottom: 22px;">{{ $greeting }}</h2>
            @endif

            
            @if (! empty($introLines))
                @foreach ($introLines as $line)
                    <p>{{ $line }}</p>
                @endforeach
            @endif

            
            @isset($actionText)
                <div style="margin: 30px 0;">
                    <a href="{{ $actionUrl }}" class="button" style="display:inline-block;background:#D51C3B;padding:14px 24px;border-radius:8px;text-decoration:none;font-weight:700;font-size:16px;color:#fff;">{{ $actionText }}</a>
                </div>
            @endisset

            
            @if (! empty($outroLines))
                @foreach ($outroLines as $line)
                    <p>{{ $line }}</p>
                @endforeach
            @endif

            
            <p style="margin-top: 30px; color:#1b243b;">
                @if (! empty($salutation))
                    {!! nl2br(e($salutation)) !!}
                @else
                    Un saludo,<br>{{ config('app.name') }}
                @endif
            </p>
        @endif
    </div>

    
    @isset($actionText)
        <div class="footer" style="font-size: 11px;">
            Si tienes problemas para hacer clic en el botón
            "{{ $actionText }}", copia y pega esta URL en tu navegador:<br>
            <span style="word-break: break-all;">{{ $displayableActionUrl ?? $actionUrl }}</span>
        </div>
    @else
        <div class="footer">
            <div style="font-weight:600; margin-bottom:6px;">Este mensaje se ha enviado automáticamente por {{ config('app.name') }}.</div>
            <div style="opacity:0.9; font-size:12px;">Contacto: <a href="mailto:{{ config('mail.from.address') }}" style="color:inherit; text-decoration:underline;">{{ config('mail.from.address') }}</a> · <a href="{{ url('/profile') }}" style="color:inherit; text-decoration:underline;">Gestionar notificaciones</a></div>
            <div style="margin-top:8px; font-size:11px; opacity:0.8;">Si no reconoces esta actividad, ponte en contacto con nosotros.</div>
        </div>
    @endisset

</div>
</body>
</html>
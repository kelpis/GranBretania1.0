<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreTranslationRequest;
use App\Models\TranslationRequest;
use Illuminate\Support\Facades\Notification;
use App\Notifications\TranslationReceived;
use App\Notifications\TranslationAdminAlert;
use Illuminate\Support\Facades\Auth;

class TranslationRequestController extends Controller
{
    // GET /traduccion
    public function create()
    {
        // La vista se movió a resources/views/user/translations/create.blade.php
        return view('user.translations.create');
    }

    // POST /traduccion
    public function store(StoreTranslationRequest $request)
    {
        $path = $request->file('file')->store('translations');

        $data = $request->safe()->except('file');
        // El usuario debe estar autenticado (ver StoreTranslationRequest::authorize).
        // Vincular la solicitud a la cuenta del usuario y no almacenar name/email
        // en la tabla si se puede evitar (transición hacia normalización).
        if (Auth::check()) {
            $data['user_id'] = Auth::id();
        }
        // Mapear consentimiento GDPR
        if (isset($data['gdpr']) && $data['gdpr']) {
            $data['gdpr_given'] = true;
            $data['gdpr_at'] = now();
            unset($data['gdpr']);
        }

        $tr = TranslationRequest::create(array_merge($data, ['file_path' => $path]));

        // Notificar al usuario autenticado (preferir notify sobre route mail)
        try {
            if ($tr->user) {
                $tr->user->notify(new TranslationReceived($tr));
            } else {
                // Fallback: use posted email if present (backwards compatibility)
                Notification::route('mail', $request->input('email'))
                    ->notify(new TranslationReceived($tr));
            }

            // Notificar al admin
            // (opcional) pequeña pausa para proveedores de testing con límites
            sleep(2);
            Notification::route('mail', env('ADMIN_EMAIL', config('mail.from.address')))
                ->notify(new TranslationAdminAlert($tr));
        } catch (\Throwable $e) {
            // No bloquear la experiencia de usuario por fallos de notificación
        }

        return back()->with('ok', 'Solicitud enviada. Revisa tu correo para el acuse.');
    }
}

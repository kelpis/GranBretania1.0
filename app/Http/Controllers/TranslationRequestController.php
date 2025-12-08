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
    // GET /traduccion form
    public function create()
    {
        
        return view('user.translations.create');
    }

    // POST /traduccion
    public function store(StoreTranslationRequest $request)
    {   //Acceder al archivo subido desde el form y lo almacena en storage/app/translation
        $path = $request->file('file')->store('translations');

        $data = $request->safe()->except('file');
        // El usuario debe estar autenticado (ver StoreTranslationRequest::authorize).
        // Vincular la solicitud a la cuenta del usuario.
        if (Auth::check()) {
            $data['user_id'] = Auth::id();
        }
        // Mapear consentimiento GDPR
        if (isset($data['gdpr']) && $data['gdpr']) {
            $data['gdpr_given'] = true;
            $data['gdpr_at'] = now();
            unset($data['gdpr']);
        }
        //Combinamos el array con los datos validados y la ruta del archivo almacenado
        $tr = TranslationRequest::create(array_merge($data, ['file_path' => $path]));

        // Notificar al usuario autenticado 
        try {
            if ($tr->user) {
                $tr->user->notify(new TranslationReceived($tr));
            } else {
                Notification::route('mail', $request->input('email'))
                    ->notify(new TranslationReceived($tr));
            }

            // Notificar al admin
            
            sleep(2);
            Notification::route('mail', env('ADMIN_EMAIL', config('mail.from.address')))
                ->notify(new TranslationAdminAlert($tr));
        } catch (\Throwable $e) {
            // No bloquear flujo de usuario por fallos de notificación
        }

        return back()->with('ok', 'Solicitud enviada. Revisa tu correo para el acuse.');
    }
}

<?php

use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\ContactMessage;

uses(RefreshDatabase::class);

it('flujo de contacto: envía formulario y guarda el mensaje en BD', function () {
    // Fake notifications and external HTTP (recaptcha)
    Notification::fake();
    Http::fake([
        'www.google.com/*' => Http::response([
            'success' => true,
            'score' => 0.9,
            'action' => 'contact'
        ], 200),
    ]);

    $data = [
        'name' => 'Prueba Contacto',
        'email' => 'contact@test.example',
        'subject' => 'Consulta test',
        'message' => 'Mensaje de prueba desde test',
        'gdpr' => '1',
        'g-recaptcha-response' => 'dummy-token'
    ];

    $request = \Illuminate\Http\Request::create(route('contact.store'), 'POST', $data);
    $response = app()->handle($request);

    // El controlador debería dejar la clave 'ok' en session
    expect(session('ok'))->not->toBeNull();

    // El mensaje debe estar en BD
    $msg = ContactMessage::where('email', $data['email'])->first();
    expect($msg)->not->toBeNull();
    expect($msg->message)->toBe('Mensaje de prueba desde test');
});

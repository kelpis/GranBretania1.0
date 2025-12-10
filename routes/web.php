<?php

use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ClassBookingController;
use App\Http\Controllers\BookingAdminController;
use App\Http\Controllers\AvailabilityAdminController;
use App\Http\Controllers\TranslationRequestController;
use App\Http\Controllers\UserBookingController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminMiddleware;
use App\Models\TranslationRequest;
use App\Http\Controllers\StripeWebhookController;
use App\Http\Middleware\VerifyCsrfToken;
use App\Http\Controllers\AdminTranslationController;


use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;



// Home 

Route::get('/', function () {
    return view('layouts.home');
});


// Paginas publicas



Route::view('/politica-privacidad', 'legal.privacy')->name('privacy');
Route::view('/cookies', 'legal.cookies')->name('cookies.policy');
Route::view('/condiciones', 'legal.condiciones')->name('condiciones');
Route::view('/aviso', 'legal.aviso')->name('aviso');
Route::view('/', 'layouts.home')->name('home');
Route::view('/clases', 'layouts.class')->name('clases');
Route::view('/traducciones', 'layouts.translate')->name('traducciones');
Route::view('/sobremi', 'layouts.aboutme')->name('sobremi');
Route::view('/faq', 'layouts.faq')->name('faq');


// Dashboard / privadas paginas

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


// Rutas de perfil (requieren autenticación)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// Admin (panel principal)

Route::middleware(['auth', AdminMiddleware::class])
    ->get('/admin', [AdminController::class, 'index'])
    ->name('admin.index');


// Contacto formulario

Route::get('/contacto', [ContactController::class, 'create'])->name('contact.create');
Route::post('/contacto', [ContactController::class, 'store'])->name('contact.store');


// Booking (reservas) - protegidas por auth

// Rutas para crear/guardar reserva
Route::middleware('auth')->group(function () {
    Route::get('/reservar', [ClassBookingController::class, 'create'])
        ->name('bookings.create');

    Route::post('/reservar', [ClassBookingController::class, 'store'])
        ->name('bookings.store');

    // Endpoint para consultar horas disponibles para una fecha
    Route::get('/reservar/disponibilidad', [ClassBookingController::class, 'availability'])
        ->name('bookings.availability');
});

// Página de éxito tras reservar
Route::get('/reservar/ok', [ClassBookingController::class, 'success'])
    ->name('bookings.success');



// Join a videollamada asociada a una reserva 
Route::get('/reservas/{booking}/unirse', [App\Http\Controllers\ClassBookingController::class, 'join'])
    ->name('bookings.join');

// Panel de usuario: mis reservas y traducciones (requieren auth)
Route::middleware('auth')->group(function () {
    Route::get('/mis-reservas', [UserBookingController::class, 'index'])->name('user.bookings.index');
    Route::get('/mis-reservas/{booking}/editar', [UserBookingController::class, 'edit'])->name('user.bookings.edit');
    Route::put('/mis-reservas/{booking}', [UserBookingController::class, 'update'])->name('user.bookings.update');
    // Redirección tras editar
    Route::get('/mis-reservas/editar/exito', [UserBookingController::class, 'editSuccess'])
        ->name('user.bookings.edit_success');

    Route::delete('/mis-reservas/{booking}', [UserBookingController::class, 'destroy'])->name('user.bookings.destroy');

  
    Route::get('/mis-traducciones/{id}/archivo', [TranslationRequestController::class, 'download'])
        ->name('user.translations.download');



    // Página dedicada: Mis traducciones (lista del usuario)
    Route::get('/mis-traducciones', [TranslationRequestController::class, 'index'])
        ->name('user.translations.index');
});



// Solicitar traducción (loggeado)

Route::middleware('auth')->group(function () {
    Route::get('/traduccion', [TranslationRequestController::class, 'create'])->name('translation.create');

    Route::post('/traduccion', [TranslationRequestController::class, 'store'])
        ->middleware('throttle:5,1')  // rate limit opcional
        ->name('translation.store');
});

// Compatibilidad: alias de nombre de ruta antiguo usado en algunas vistas
Route::get('/translation-requests-redirect', function () {
    return redirect()->route('user.translations.index');
})->name('translation.requests');


// Admin routes (prefijo /admin) PROTEGIDAS

Route::middleware(['auth', AdminMiddleware::class])
    ->prefix('admin')->name('admin.')->group(function () {

        // Traducciones (panel admin)
        Route::get('/traducciones', [AdminTranslationController::class, 'index'])
            ->name('translations.index');

        Route::get('/traducciones/{id}/archivo', [AdminTranslationController::class, 'download'])
            ->name('translations.download');

        //TRADUCCION: asignar precio y generar enlace de pago
        Route::post('/traducciones/{translation}/presupuesto', [AdminTranslationController::class, 'quote'])
            ->name('translations.quote');


        Route::post('/traducciones/{translation}/entregar', [AdminTranslationController::class, 'deliver'])
            ->name('translations.deliver');


        // Clases reservadas del admin
        Route::get('/reservas', [BookingAdminController::class, 'index'])->name('bookings.index');
        Route::patch('/reservas/{booking}/confirmar', [BookingAdminController::class, 'confirm'])->name('bookings.confirm');
        Route::patch('/reservas/{booking}/cancelar', [BookingAdminController::class, 'cancel'])->name('bookings.cancel');

        // Franjas horarias (disponibilidad)
        Route::get('/disponibilidad', [AvailabilityAdminController::class, 'index'])->name('availability.index');
        Route::post('/disponibilidad', [AvailabilityAdminController::class, 'store'])->name('availability.store');
        Route::post('/disponibilidad/generar', [AvailabilityAdminController::class, 'generate'])->name('availability.generate');
        Route::patch('/disponibilidad/{slot}/toggle', [AvailabilityAdminController::class, 'toggle'])->name('availability.toggle');
        Route::delete('/disponibilidad/{slot}', [AvailabilityAdminController::class, 'destroy'])->name('availability.destroy');


        // Devolver pago
        Route::post('/bookings/{booking}/refund', [AdminController::class, 'refund'])
            ->name('bookings.refund');
    });



// Auth routes (login/register/etc.)
require __DIR__ . '/auth.php';



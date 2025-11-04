<?php
// script para enviar una notificación de prueba al correo del usuario de la última reserva
$vendor = __DIR__ . '/../vendor/autoload.php';
if (! file_exists($vendor)) {
    echo "vendor_missing\n";
    exit(1);
}
require $vendor;
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $b = \App\Models\ClassBooking::latest()->first();
    if (! $b) {
        echo "no_booking\n";
        exit(0);
    }

    echo "booking_email:" . ($b->email ?? 'null') . "\n";

    \Illuminate\Support\Facades\Notification::route('mail', $b->email)
        ->notifyNow(new \App\Notifications\BookingReceived($b));

    echo "ok\n";
} catch (\Throwable $e) {
    echo "err: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
    exit(1);
}

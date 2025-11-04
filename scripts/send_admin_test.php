<?php
// script para enviar una notificación de prueba al ADMIN_EMAIL
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

    \Illuminate\Support\Facades\Notification::route('mail', getenv('ADMIN_EMAIL'))
        ->notifyNow(new \App\Notifications\BookingAdminNotification($b));

    echo "ok\n";
} catch (\Throwable $e) {
    echo "err: " . $e->getMessage() . "\n";
    // imprimir traza corta
    echo $e->getTraceAsString() . "\n";
    exit(1);
}

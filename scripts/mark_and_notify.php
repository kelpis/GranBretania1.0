<?php
// marca la última reserva no pagada como pagada y envía notificaciones inmediatamente (notifyNow)
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
    $b = \App\Models\ClassBooking::where('paid', 0)->orderBy('id', 'desc')->first();
    if (! $b) {
        echo "no_unpaid_booking\n";
        exit(0);
    }

    $b->paid = 1;
    $b->paid_at = now();
    $b->payment_intent = 'simulated_test_' . uniqid();
    $b->amount_paid = $b->price ?? 2500;
    $b->currency = 'eur';
    $b->save();

    \Illuminate\Support\Facades\Log::info('mark_and_notify: booking marked paid', ['booking_id' => $b->id]);

    // Notificar al usuario y admin inmediatamente (sin cola)
    \Illuminate\Support\Facades\Notification::route('mail', $b->email)
        ->notifyNow(new \App\Notifications\BookingReceived($b));

    sleep(1);

    \Illuminate\Support\Facades\Notification::route('mail', getenv('ADMIN_EMAIL'))
        ->notifyNow(new \App\Notifications\BookingAdminNotification($b));

    echo "marked_and_notified:{$b->id}\n";
} catch (\Throwable $e) {
    echo "err: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
    exit(1);
}

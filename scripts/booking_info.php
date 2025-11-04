<?php
$vendor = __DIR__ . '/../vendor/autoload.php';
if (! file_exists($vendor)) { echo "vendor_missing\n"; exit(1); }
require $vendor;
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$b = \App\Models\ClassBooking::latest()->first();
if (! $b) { echo "no_booking\n"; exit(0); }
echo "id:" . $b->id . "\n";
echo "email:" . ($b->email ?? 'null') . "\n";
echo "paid:" . ($b->paid ? '1' : '0') . "\n";
echo "paid_at:" . ($b->paid_at ? $b->paid_at : 'null') . "\n";
echo "payment_intent:" . ($b->payment_intent ?? 'null') . "\n";

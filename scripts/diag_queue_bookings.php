<?php
$vendor = __DIR__ . '/../vendor/autoload.php';
if (! file_exists($vendor)) { echo "vendor_missing\n"; exit(1); }
require $vendor;
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "ENV QUEUE_CONNECTION=" . env('QUEUE_CONNECTION') . "\n";
echo "STRIPE_WEBHOOK_SECRET=" . (env('STRIPE_WEBHOOK_SECRET') ? 'present' : 'missing') . "\n";

try {
    $unpaidCount = DB::table('class_bookings')->where('paid', 0)->count();
    echo "unpaid_count: $unpaidCount\n";
    $recentUnpaid = DB::table('class_bookings')->where('paid',0)->orderBy('id','desc')->limit(10)->get();
    echo "recent_unpaid (last 10):\n";
    foreach ($recentUnpaid as $b) {
        echo "- id={$b->id} email={$b->email} class_date={$b->class_date} class_time={$b->class_time} created_at={$b->created_at}\n";
    }

    $jobs = DB::table('jobs')->count();
    $failed = DB::table('failed_jobs')->count();
    echo "jobs_count: $jobs\n";
    echo "failed_jobs_count: $failed\n";

    $lastJobs = DB::table('jobs')->orderBy('id','desc')->limit(5)->get();
    echo "last_jobs:\n";
    foreach ($lastJobs as $j) {
        echo "- id={$j->id} attempts={$j->attempts} queue={$j->queue} payload_snippet=" . substr($j->payload,0,200) . "...\n";
    }

    $lastFailed = DB::table('failed_jobs')->orderBy('id','desc')->limit(5)->get();
    echo "last_failed_jobs:\n";
    foreach ($lastFailed as $f) {
        echo "- id={$f->id} connection={$f->connection} queue={$f->queue} exception_snippet=" . substr($f->exception,0,300) . "...\n";
    }
} catch (\Exception $e) {
    echo "DB error: " . $e->getMessage() . "\n";
    exit(1);
}

// tail webhook/mail related lines
$log = __DIR__ . '/../storage/logs/laravel.log';
if (file_exists($log)) {
    $lines = array_slice(file($log), -500);
    echo "\n--- last log lines (stripe/webhook/mail related) ---\n";
    foreach ($lines as $line) {
        if (stripos($line,'stripe')!==false || stripos($line,'checkout.session.completed')!==false || stripos($line,'webhook')!==false || stripos($line,'Invalid signature')!==false || stripos($line,'Invalid payload')!==false || stripos($line,'Stripe Checkout creation failed')!==false || stripos($line,'BookingReceived')!==false || stripos($line,'BookingAdminNotification')!==false || stripos($line,'mail')!==false || stripos($line,'smtp')!==false) {
            echo $line;
        }
    }
} else {
    echo "log file missing\n";
}

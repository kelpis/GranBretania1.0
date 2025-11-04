<?php
$vendor = __DIR__ . '/../vendor/autoload.php';
if (! file_exists($vendor)) { echo "vendor_missing\n"; exit(1); }
require $vendor;
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

echo "QUEUE_CONNECTION=" . env('QUEUE_CONNECTION') . "\n";
echo "MAIL_MAILER=" . env('MAIL_MAILER') . "\n";
echo "MAIL_HOST=" . env('MAIL_HOST') . "\n";
echo "MAIL_USERNAME=" . env('MAIL_USERNAME') . "\n";

try {
    $jobs = DB::table('jobs')->count();
    $failed = DB::table('failed_jobs')->count();
    echo "jobs_count: $jobs\n";
    echo "failed_jobs_count: $failed\n";

    $lastJobs = DB::table('jobs')->orderBy('id','desc')->limit(5)->get();
    echo "last_jobs:\n";
    foreach ($lastJobs as $j) {
        echo "- id={$j->id} queue={$j->queue} attempts={$j->attempts} payload_snippet=" . substr($j->payload,0,200) . "...\n";
    }

    $lastFailed = DB::table('failed_jobs')->orderBy('id','desc')->limit(5)->get();
    echo "last_failed_jobs:\n";
    foreach ($lastFailed as $f) {
        echo "- id={$f->id} connection={$f->connection} queue={$f->queue} exception_snippet=" . substr($f->exception,0,200) . "...\n";
    }
} catch (\Exception $e) {
    echo "DB error: " . $e->getMessage() . "\n";
}

// tail mail-related lines from the log
$log = __DIR__ . '/../storage/logs/laravel.log';
if (file_exists($log)) {
    $lines = array_slice(file($log), -200);
    echo "\n--- last log lines (mail-related) ---\n";
    foreach ($lines as $line) {
        if (stripos($line,'mail')!==false || stripos($line,'smtp')!==false || stripos($line,'mailtrap')!==false || stripos($line,'ethereal')!==false || stripos($line,'mailer')!==false || stripos($line,'BookingReceived')!==false || stripos($line,'BookingAdminNotification')!==false) {
            echo $line;
        }
    }
} else {
    echo "log file missing\n";
}

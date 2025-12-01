<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ClassBooking;
use Illuminate\Support\Facades\Log;

class CleanExpiredHolds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:clean-expired-holds';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mark pending bookings whose hold expired as expired and free the slot';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = now();
        $expired = ClassBooking::where('status', 'pending')
            ->where('paid', false)
            ->whereNotNull('reserved_until')
            ->where('reserved_until', '<', $now)
            ->get();

        $count = 0;
        foreach ($expired as $b) {
            try {
                $b->status = 'expired';
                $b->save();
                $count++;
            } catch (\Throwable $e) {
                Log::warning('Failed to expire hold for booking ' . $b->id . ': ' . $e->getMessage());
            }
        }

        $this->info("Expired holds cleaned: {$count}");
        return 0;
    }
}

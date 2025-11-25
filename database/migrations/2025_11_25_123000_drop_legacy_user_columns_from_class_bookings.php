<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Safety check: abort if any booking still lacks user_id
        $orphans = DB::table('class_bookings')->whereNull('user_id')->count();
        if ($orphans > 0) {
            throw new \Exception("Abort: {$orphans} class_bookings rows have null user_id. Assign users before dropping legacy columns.");
        }

        Schema::table('class_bookings', function (Blueprint $table) {
            $toDrop = [];
            if (Schema::hasColumn('class_bookings', 'name')) {
                $toDrop[] = 'name';
            }
            if (Schema::hasColumn('class_bookings', 'email')) {
                $toDrop[] = 'email';
            }
            if (Schema::hasColumn('class_bookings', 'phone')) {
                $toDrop[] = 'phone';
            }

            if (! empty($toDrop)) {
                $table->dropColumn($toDrop);
            }
        });

        Log::info('Migration: dropped legacy columns from class_bookings', ['dropped' => ['name','email','phone']]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('class_bookings', function (Blueprint $table) {
            if (! Schema::hasColumn('class_bookings', 'name')) {
                $table->string('name')->after('class_time');
            }
            if (! Schema::hasColumn('class_bookings', 'email')) {
                $table->string('email')->after('name');
            }
            if (! Schema::hasColumn('class_bookings', 'phone')) {
                $table->string('phone')->nullable()->after('email');
            }
        });

        Log::info('Migration: restored legacy columns on class_bookings');
    }
};

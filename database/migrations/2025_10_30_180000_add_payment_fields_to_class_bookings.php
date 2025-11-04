<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('class_bookings', function (Blueprint $table) {
            $table->boolean('paid')->default(false)->after('status');
            $table->timestamp('paid_at')->nullable()->after('paid');
            $table->string('payment_intent')->nullable()->after('paid_at');
            $table->integer('amount_paid')->nullable()->after('payment_intent');
            $table->string('currency')->nullable()->after('amount_paid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('class_bookings', function (Blueprint $table) {
            $table->dropColumn(['paid', 'paid_at', 'payment_intent', 'amount_paid', 'currency']);
        });
    }
};

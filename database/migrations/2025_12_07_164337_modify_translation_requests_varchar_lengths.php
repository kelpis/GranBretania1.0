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
        Schema::table('translation_requests', function (Blueprint $table) {
            $table->string('urgency', 10)->change();
            $table->string('status', 20)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('translation_requests', function (Blueprint $table) {
            $table->string('urgency', 255)->change();
            $table->string('status', 255)->change();
        });
    }
};

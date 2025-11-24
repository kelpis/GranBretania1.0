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
            // Teléfono: de varchar(255) a varchar(20)
            $table->string('phone', 20)->nullable()->change();

            // Moneda: de varchar(255) a char(3) (ej: 'eur')
            $table->char('currency', 3)->nullable()->change();

            // Cantidad pagada: de INT a DECIMAL(8,2)
            $table->decimal('amount_paid', 8, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};

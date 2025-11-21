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
            // Estado de la traducción:
            // pending = enviada, quoted = con presupuesto asignado,
            // paid = pagada, delivered = entrega final subida.
            $table->string('status')
                ->default('pending')
                ->after('user_id');

            // Precio final calculado por la admin (en céntimos)
            $table->integer('final_price_cents')
                ->nullable()
                ->after('status');

            // Moneda para Stripe (por ahora siempre EUR)
            $table->string('currency', 3)
                ->default('eur')
                ->after('final_price_cents');

            // Datos del pago Stripe asociados a la traducción
            $table->string('stripe_session_id')
                ->nullable()
                ->after('currency');

            $table->string('payment_intent')
                ->nullable()
                ->after('stripe_session_id');

            $table->timestamp('paid_at')
                ->nullable()
                ->after('payment_intent');

            // Fichero final traducido + fecha de entrega
            $table->string('output_file_path')
                ->nullable()
                ->after('paid_at');

            $table->timestamp('delivered_at')
                ->nullable()
                ->after('output_file_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('translation_requests', function (Blueprint $table) {
            $table->dropColumn([
                'status',
                'final_price_cents',
                'currency',
                'stripe_session_id',
                'payment_intent',
                'paid_at',
                'output_file_path',
                'delivered_at',
            ]);
        });
    }
};

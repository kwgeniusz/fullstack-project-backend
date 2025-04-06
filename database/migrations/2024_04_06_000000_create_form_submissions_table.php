<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('form_submissions', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('email');
            $table->text('mensaje');
            $table->enum('tipo_comprobante', ['Factura', 'CFF', 'Ticket']);
            $table->enum('metodo_pago', ['Efectivo', 'Transferencia', 'Tarjeta']);
            $table->string('comprobante_pago')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('form_submissions');
    }
};

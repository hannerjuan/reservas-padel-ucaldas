<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('price_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('space_id')->constrained()->onDelete('cascade');
            $table->integer('day_of_week'); // 0 = Domingo, 1 = Lunes, etc.
            $table->time('start_time');     // Hora de inicio de la tarifa especial
            $table->time('end_time');       // Hora de fin de la tarifa especial
            $table->decimal('price_per_hour', 10, 2); // Nuevo precio para esta franja
            $table->string('label')->nullable(); // Ejem: "Tarifa Nocturna / Hora Pico"
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('price_rules');
    }
};
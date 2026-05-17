<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Ej: Corte de Cabello Fiel o Perfilado de Barba
            $table->text('description')->nullable();
            $table->decimal('price', 8, 2); // Precio del servicio
            $table->integer('duration_minutes')->default(30); // Tiempo estimado en minutos
            $table->timestamps();
            $table->softDeletes(); // Requisito de la rúbrica (Soft Deletes)
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
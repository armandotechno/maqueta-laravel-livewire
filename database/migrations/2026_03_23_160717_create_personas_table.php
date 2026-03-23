<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('personas', function (Blueprint $table) {
            $table->id();

            $table->string('cedula')->unique();
            $table->string('primer_nombre');
            $table->string('segundo_nombre')->nullable();
            $table->string('primer_apellido');
            $table->string('segundo_apellido')->nullable();

            $table->date('fecha_nacimiento');
            $table->char('sexo', 1);

            // Campos de ubicación
            $table->string('estado')->nullable();
            $table->string('municipio')->nullable();
            $table->string('parroquia')->nullable();


            $table->timestampsTz();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personas');
    }
};

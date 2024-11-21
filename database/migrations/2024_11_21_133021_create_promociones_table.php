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
        Schema::create('promociones', function (Blueprint $table) {
            $table->id();
            $table->string("nombre_promocion", 50); //Black Friaay
            $table->double("porcentaje_descuento"); //50%
            $table->dateTime("fecha_inicio"); //2024-11-21 00:00:00
            $table->dateTime("fecha_fin"); //2024-11-21 23:59:59
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promociones');
    }
};

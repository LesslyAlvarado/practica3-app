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
        Schema::table('perfil_permisos', function (Blueprint $table) {
            $table->unsignedBigInteger("permiso_id")->nullable(true);
            $table->foreign("permiso_id")->references("id")->on("permisos");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('perfil_permisos', function (Blueprint $table) {
            $table->dropForeign("permiso_id");
            $table->dropColumn("permiso_id");
        });
    }
};

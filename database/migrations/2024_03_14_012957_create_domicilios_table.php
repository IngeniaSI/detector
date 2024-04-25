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
        Schema::create('domicilios', function (Blueprint $table) {
            $table->id();
            $table->string('calle')->nullable();
            $table->string('numero_exterior')->nullable();
            $table->string('numero_interior')->nullable();
            $table->float('latitud', 20, 15)->nullable();
            $table->float('longitud', 20, 15)->nullable();
            $table->foreignId('colonia_id')->nullable()->constrained();
            $table->foreignId('identificacion_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('domicilios');
    }
};

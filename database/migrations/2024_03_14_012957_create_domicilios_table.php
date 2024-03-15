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
            $table->string('calle');
            $table->integer('numero_exterior');
            $table->integer('numero_interior')->nullable();
            $table->float('latitud')->nullable();
            $table->float('longitud')->nullable();
            $table->foreignId('colonia_id')->constrained();
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

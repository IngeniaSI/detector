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
        Schema::create('identificacions', function (Blueprint $table) {
            $table->id();
            $table->string('clave_elector')->nullable();
            $table->string('curp')->nullable();
            $table->foreignId('persona_id')->constrained();
            $table->foreignId('seccion_id')->nullable()->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('identificacions');
    }
};

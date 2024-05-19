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
        Schema::create('respuestas', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('folio');
            $table->text('jsonRespuestas');
            $table->string('origen');
            $table->foreignId('persona_id')->nullable()->constrained();
            $table->foreignId('encuesta_id')->constrained();
            $table->string('nombres')->nullable();
            $table->string('apellidos')->nullable();
            $table->string('telefono')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('respuestas');
    }
};

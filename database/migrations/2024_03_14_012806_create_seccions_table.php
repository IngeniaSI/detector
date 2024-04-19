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
        Schema::create('seccions', function (Blueprint $table) {
            $table->id();
            $table->string('tipo')->nullable();
            $table->foreignId('distrito_local_id')->constrained();
            $table->integer('poblacion')->default(0);
            $table->integer('objetivo')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seccions');
    }
};

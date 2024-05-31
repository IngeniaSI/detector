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
        Schema::create('oportunidads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('promotor_id')->nullable();
            $table->foreignId('objetivo_id')->constrained();
            $table->foreignId('persona_id')->constrained();
            $table->string('estatus')->default('pendiente');
            $table->date('deleted_at')->nullable();
            $table->timestamps();

            $table->foreign('promotor_id')->references('id')->on('personas')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oportunidads');
    }
};

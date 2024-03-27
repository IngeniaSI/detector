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
        Schema::create('personas', function (Blueprint $table) {
            $table->id();
            $table->dateTime('fecha_registro')->nullable();
            $table->integer('folio')->nullable();
            $table->foreignId('persona_id')->nullable()->constrained();
            $table->string('nombres');
            $table->string('apellido_paterno');
            $table->string('apellido_materno')->nullable();
            $table->string('genero');
            $table->string('telefono_celular')->nullable();
            $table->string('telefono_fijo')->nullable();
            $table->string('correo')->nullable();
            $table->string('nombre_en_facebook')->nullable();
            $table->string('escolaridad')->nullable();
            $table->string('afiliado')->nullable();
            $table->string('simpatizante')->nullable();
            $table->string('programa')->nullable();
            $table->string('funcion_en_campania')->nullable();
            $table->dateTime('fecha_nacimiento')->nullable();
            $table->string('rango_edad')->nullable();
            $table->string('observaciones')->nullable();
            $table->string('etiquetas')->nullable();
            $table->string('rolEstructura')->nullable();
            $table->bigInteger('rolNumero')->nullable();
            $table->boolean('supervisado')->default(0);
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personas');
    }
};

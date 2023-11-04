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
        Schema::create('evento_recaudacion', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_del_evento');
            $table->timestamp('fecha');
            $table->string('ubicacion');
            $table->text('descripcion');
            $table->unsignedBigInteger('id_estado');
            $table->unsignedBigInteger('id_del_usuario');
            $table->unsignedBigInteger('id_tipo_evento');
        
            $table->foreign('id_estado')->references('id')->on('estado_evento');
            $table->foreign('id_del_usuario')->references('id')->on('usuario'); // Asumiendo que tienes una tabla de usuarios
            $table->foreign('id_tipo_evento')->references('id_tipo_evento')->on('tipo_evento');
        
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evento_recaudacion');
    }
};

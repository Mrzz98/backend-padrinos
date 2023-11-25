<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('solicitud_de_adopcion', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_del_usuario');
            $table->unsignedBigInteger('id_del_adoptante');
            $table->unsignedBigInteger('id_del_animal');
            $table->date('fecha_solicitud');
            $table->string('datos_adicionales')->nullable();
            $table->boolean('estado');
            $table->timestamps();

            // Claves foráneas
            $table->foreign('id_del_usuario')->references('id')->on('usuario');
            $table->foreign('id_del_adoptante')->references('id')->on('adoptantes');
            $table->foreign('id_del_animal')->references('id')->on('animales');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('solicitud_de_adopcion');
    }
};

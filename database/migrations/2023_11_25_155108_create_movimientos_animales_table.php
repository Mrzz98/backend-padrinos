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
        Schema::create('movimientos_animales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_solicitud');
            $table->unsignedBigInteger('id_rescate');
            $table->unsignedBigInteger('id_usuario');
            $table->date('fecha_movimiento');
            $table->timestamps();

            $table->foreign('id_solicitud')->references('id')->on('solicitud_de_adopcion');
            $table->foreign('id_rescate')->references('id')->on('rescates');
            $table->foreign('id_usuario')->references('id')->on('usuario');
        });
    }

    public function down()
    {
        Schema::dropIfExists('movimientos_animales');
    }
};

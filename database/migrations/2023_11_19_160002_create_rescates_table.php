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
        Schema::create('rescates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_usuario')->constrained('usuarios');
            $table->string('estado');
            $table->string('direccion');
            $table->timestamp('fecha_rescate');
            $table->text('informacion_adicional')->nullable();
            $table->foreignId('id_animal')->constrained('animales');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rescates');
    }
};

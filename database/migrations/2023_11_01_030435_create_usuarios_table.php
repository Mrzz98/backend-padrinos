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
        Schema::create('usuario', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('apellido');
            $table->string('nombre_usuario')->unique(); // Campo único para el nombre de usuario
            $table->string('contrasena');
            $table->string('correo_electronico')->unique(); // Campo único para el correo electrónico
            $table->string('rol'); // Agrega un campo para el rol si es necesario
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('usuario');
    }
};

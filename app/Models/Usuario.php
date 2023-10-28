<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table = 'Usuario'; // Nombre de la tabla en la base de datos
    protected $primaryKey = 'id'; // Clave primaria
    public $timestamps = false; // Si no tienes las columnas created_at y updated_at

    protected $fillable = [
        'nombre',
        'apellido',
        'nombre_usuario',
        'contrasena',
        'correo_electronico',
        'rol',
    ];
}

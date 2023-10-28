<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class Usuario extends Authenticatable implements AuthenticatableContract
{
    // use HasApiTokens;
    protected $table = 'Usuario'; // Nombre de la tabla en la base de datos
    protected $primaryKey = 'id'; // Clave primaria
    public $timestamps = false; // Si no tienes las columnas created_at y updated_at

    protected $username = 'nombre_usuario';

    // Indica a Laravel que el campo para la contraseña es "contrasena"
    public function getAuthPassword()
    {
        return $this->contrasena;
    }

    protected $fillable = [
        'nombre',
        'apellido',
        'nombre_usuario',
        'contrasena',
        'correo_electronico',
        'rol',
    ];
}

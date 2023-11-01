<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class Usuario extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory;
    // use HasApiTokens;
    protected $table = 'usuario'; // Nombre de la tabla en la base de datos
    protected $primaryKey = 'id'; // Clave primaria
    public $timestamps = false; // Si no tienes las columnas created_at y updated_at

    protected $username = 'nombre_usuario';

    // Indica a Laravel que el campo para la contraseña es "contrasena"
    public function getAuthPassword()
    {
        return $this->contrasena;
    }

    protected $hidden = [
        'contrasena', 'remember_token',
    ];

    protected $fillable = [
        'nombre',
        'apellido',
        'nombre_usuario',
        'contrasena',
        'correo_electronico',
        'rol',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }
}

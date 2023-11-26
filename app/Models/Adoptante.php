<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adoptante extends Model
{
    use HasFactory;
    protected $table = 'adoptantes';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'ci',
        'nombre',
        'apellido',
        'correo_electronico',
        'telefono',
        'direccion',
        'ocupacion',
    ];

}

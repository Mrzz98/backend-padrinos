<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rescate extends Model
{
    use HasFactory;
    protected $table = 'rescates';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'id_usuario',
        'direccion',
        'estado',
        'fecha_rescate',
        'informacion_adicional',
        'id_animal',
    ];
}

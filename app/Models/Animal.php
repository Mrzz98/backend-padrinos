<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Animal extends Model
{
    use HasFactory;
    public function rescate()
    {
        return $this->hasOne(Rescate::class, 'id_animal');
    }
    protected $table = 'animales'; // Nombre de la tabla en la base de datos
    protected $primaryKey = 'id'; // Clave primaria
    public $timestamps = true; // Si no tienes las columnas created_at y updated_at

    protected $fillable = [
        'nombre',
        'especie',
        'tamano',
        'edad',
        'descripcion',
        'image_path'
    ];

    // Otras propiedades, como $hidden, $casts, $dates, etc., si es necesario

    // Métodos adicionales del modelo si es necesario

}
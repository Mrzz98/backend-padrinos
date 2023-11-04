<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoEvento extends Model
{
    use HasFactory;
    public $timestamps = True; // Si no tienes las columnas created_at y updated_at
    protected $fillable = [
        'nombre',
    ];
}

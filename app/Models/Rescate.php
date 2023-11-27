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
    public function animal()
    {
        return $this->belongsTo(Animal::class, 'id_animal');
    }

    protected $fillable = [
        'id_usuario',
        'direccion',
        'estado',
        'fecha_rescate',
        'informacion_adicional',
        'id_animal',
    ];
}

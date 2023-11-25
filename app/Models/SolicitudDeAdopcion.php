<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudDeAdopcion extends Model
{
    use HasFactory;

    protected $table = 'solicitud_de_adopcion';

    protected $fillable = [
        'id_del_usuario',
        'id_del_adoptante',
        'id_del_animal',
        'fecha_solicitud',
        'datos_adicionales',
        'estado',
    ];

    // Definir relaciones
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_del_usuario');
    }

    public function adoptante()
    {
        return $this->belongsTo(Adoptante::class, 'id_del_adoptante');
    }

    public function animal()
    {
        return $this->belongsTo(Animal::class, 'id_del_animal');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventoRecaudacion extends Model
{
    use HasFactory;

    protected $table = 'evento_recaudacion'; // Nombre personalizado de la tabla

    protected $fillable = [
        'nombre_del_evento', 'fecha', 'ubicacion', 'descripcion', 'id_estado', 'id_del_usuario', 'id_tipo_evento'
    ];

    public $timestamps = true; // Habilita el registro de timestamps

    public function estadoEvento()
    {
        return $this->belongsTo(EstadoEvento::class, 'id_estado');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_del_usuario');
    }

    public function tipoEvento()
    {
        return $this->belongsTo(TipoEvento::class, 'id_tipo_evento');
    }


}

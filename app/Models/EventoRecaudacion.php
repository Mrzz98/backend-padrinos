<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventoRecaudacion extends Model
{
    use HasFactory;

    protected $table = 'evento_recaudacion';

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

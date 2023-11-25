<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovimientosAnimales extends Model
{
    use HasFactory;

    protected $table = 'movimientos_animales';

    protected $fillable = [
        'id_solicitud',
        'id_rescate',
        'id_usuario',
        'fecha_movimiento',
    ];

    public function solicitudDeAdopcion()
    {
        return $this->belongsTo(SolicitudDeAdopcion::class, 'id_solicitud');
    }

    public function rescate()
    {
        return $this->belongsTo(Rescates::class, 'id_rescate');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }
}

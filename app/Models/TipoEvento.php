<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoEvento extends Model
{
    use HasFactory;
    protected $table = 'tipo_evento'; // Nombre personalizado de la tabla

    protected $fillable = ['descripcion']; // Campos que se pueden llenar

    public $timestamps = true; // Habilita el registro de timestamps

    // Relación con EventoRecaudacion (uno a muchos)
    public function eventosRecaudacion()
    {
        return $this->hasMany(EventoRecaudacion::class, 'id_tipo_evento', 'id_tipo_evento');
    }
}

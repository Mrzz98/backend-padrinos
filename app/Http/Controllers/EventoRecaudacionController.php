<?php

namespace App\Http\Controllers;

use App\Models\EventoRecaudacion;
use Illuminate\Http\Request;

class EventoRecaudacionController extends Controller
{
    /**
     * @OA\Schema(
     *     schema="evento_recaudacion",
     *     title="Evento de Recaudación",
     *     @OA\Property(property="id", type="integer"),
     *     @OA\Property(property="nombre_del_evento", type="string"),
     *     @OA\Property(property="fecha", type="string", format="date"),
     *     @OA\Property(property="ubicacion", type="string"),
     *     @OA\Property(property="descripcion", type="string"),
     *     @OA\Property(property="id_estado", type="integer"),
     *     @OA\Property(property="id_del_usuario", type="integer"),
     *     @OA\Property(property="id_tipo_evento", type="integer"),
     *     @OA\Property(property="created_at", type="string", format="date-time"),
     *     @OA\Property(property="updated_at", type="string", format="date-time"),
     *     @OA\Property(
     *         property="estadoEvento",
     *         type="object",
     *         ref="#/components/schemas/estado_evento"
     *     ),
     *     @OA\Property(
     *         property="usuario",
     *         type="object",
     *         ref="#/components/schemas/usuario"
     *     ),
     *     @OA\Property(
     *         property="tipoEvento",
     *         type="object",
     *         ref="#/components/schemas/tipo_evento"
     *     ),
     * )
     */

    public function index()
    {
        $eventos = EventoRecaudacion::all();
        return response()->json($eventos);
    }

    public function store(Request $request)
    {
        $evento = EventoRecaudacion::create($request->all());
        return response()->json($evento, 201);
    }

    public function show(EventoRecaudacion $evento)
    {
        return response()->json($evento);
    }

    public function update(Request $request, EventoRecaudacion $evento)
    {
        $evento->update($request->all());
        return response()->json($evento);
    }

    public function destroy(EventoRecaudacion $evento)
    {
        $evento->delete();
        return response()->json(null, 204);
    }
}

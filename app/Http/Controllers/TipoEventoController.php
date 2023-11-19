<?php

namespace App\Http\Controllers;

use App\Models\TipoEvento;
use Illuminate\Http\Request;

class TipoEventoController extends Controller
{
    /**
     * @OA\Schema(
     *     schema="tipo_evento",
     *     title="Tipo de Evento",
     *     @OA\Property(property="id", type="integer"),
     *     @OA\Property(property="descripcion", type="string"),
     *     @OA\Property(property="created_at", type="string", format="date-time"),
     *     @OA\Property(property="updated_at", type="string", format="date-time"),
     * )
     */

    public function index()
    {
        $tipos = TipoEvento::all();
        return response()->json($tipos);
    }

    public function store(Request $request)
    {
        $tipo = TipoEvento::create($request->all());
        return response()->json($tipo, 201);
    }

    public function show(TipoEvento $tipo)
    {
        return response()->json($tipo);
    }

    public function update(Request $request, TipoEvento $tipo)
    {
        $tipo->update($request->all());
        return response()->json($tipo);
    }

    public function destroy(TipoEvento $tipo)
    {
        $tipo->delete();
        return response()->json(null, 204);
    }
}

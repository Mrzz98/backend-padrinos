<?php

namespace App\Http\Controllers;

use App\Models\EstadoEvento;
use Illuminate\Http\Request;

class EstadoEventoController extends Controller
{
    public function index()
    {
        $estados = EstadoEvento::all();
        return response()->json($estados);
    }

    public function store(Request $request)
    {
        $estado = EstadoEvento::create($request->all());
        return response()->json($estado, 201);
    }

    public function show(EstadoEvento $estado)
    {
        return response()->json($estado);
    }

    public function update(Request $request, EstadoEvento $estado)
    {
        $estado->update($request->all());
        return response()->json($estado);
    }

    public function destroy(EstadoEvento $estado)
    {
        $estado->delete();
        return response()->json(null, 204);
    }
}

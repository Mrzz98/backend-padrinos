<?php

namespace App\Http\Controllers;

use App\Models\TipoEvento;
use Illuminate\Http\Request;

class TipoEventoController extends Controller
{
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

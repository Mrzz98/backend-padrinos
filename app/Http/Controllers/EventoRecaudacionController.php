<?php

namespace App\Http\Controllers;

use App\Models\EventoRecaudacion;
use Illuminate\Http\Request;

class EventoRecaudacionController extends Controller
{
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

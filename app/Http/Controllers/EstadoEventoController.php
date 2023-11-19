<?php

namespace App\Http\Controllers;

use App\Models\EstadoEvento;
use Illuminate\Http\Request;

/**
 * @OA\Schema(
 *     schema="estado_evento",
 *     title="estado_evento",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="nombre", type="string"),
 *     @OA\Property(property="descripcion", type="string"),
 * )
 */
class EstadoEventoController extends Controller
{

    /**
     * @OA\Get(
     *     path="/estados-evento",
     *     tags={"Estados de Evento"},
     *     summary="Obtener todos los estados de eventos",
     *     description="Recupera todos los estados de eventos de la base de datos.",
     *     operationId="indexEstEv",
     *     @OA\Response(
     *         response=200,
     *         description="Lista de estados de eventos",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/estado_evento")
     *         )
     *     )
     * )
     */
    public function index()
    {
        $estados = EstadoEvento::all();
        return response()->json($estados);
    }

    /**
     * @OA\Post(
     *     path="/estados-evento",
     *     tags={"Estados de Evento"},
     *     summary="Registrar un nuevo estado de evento",
     *     description="Crear un nuevo estado de evento con la información proporcionada.",
     *     operationId="storeEstEv",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos del estado de evento",
     *         @OA\JsonContent(
     *             required={"nombre"},
     *             @OA\Property(property="nombre", type="string", format="string", example="Nombre del Estado"),
     *             @OA\Property(property="descripcion", type="string", format="string", example="Descripción del Estado"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Estado de evento registrado exitosamente",
     *         @OA\JsonContent(
     *             type="object",
     *             ref="#/components/schemas/estado_evento"
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validación fallida",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Error de validación"),
     *             @OA\Property(property="errors", type="object"),
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $estado = EstadoEvento::create($request->all());
        return response()->json($estado, 201);
    }

    /**
     * @OA\Get(
     *     path="/estados-evento/{estado}",
     *     tags={"Estados de Evento"},
     *     summary="Obtener información de un estado de evento",
     *     description="Obtiene información de un estado de evento por su ID.",
     *     operationId="showEstEv",
     *     @OA\Parameter(
     *         name="estado",
     *         in="path",
     *         description="ID del estado de evento",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Información del estado de evento",
     *         @OA\JsonContent(
     *             type="object",
     *             ref="#/components/schemas/estado_evento"
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Estado de evento no encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Estado de evento no encontrado"),
     *         )
     *     )
     * )
     */
    public function show(EstadoEvento $estado)
    {
        return response()->json($estado);
    }

    /**
     * @OA\Put(
     *     path="/estados-evento/{estado}",
     *     tags={"Estados de Evento"},
     *     summary="Actualizar un estado de evento",
     *     description="Actualiza un estado de evento existente con la información proporcionada.",
     *     operationId="updateEstEv",
     *     @OA\Parameter(
     *         name="estado",
     *         in="path",
     *         description="ID del estado de evento",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos actualizados del estado de evento",
     *         @OA\JsonContent(
     *             required={"nombre"},
     *             @OA\Property(property="nombre", type="string", format="string", example="Nuevo Nombre del Estado"),
     *             @OA\Property(property="descripcion", type="string", format="string", example="Nueva Descripción del Estado"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Estado de evento actualizado exitosamente",
     *         @OA\JsonContent(
     *             type="object",
     *             ref="#/components/schemas/estado_evento"
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Estado de evento no encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Estado de evento no encontrado"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validación fallida",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Error de validación"),
     *             @OA\Property(property="errors", type="object"),
     *         )
     *     )
     * )
     */
    public function update(Request $request, EstadoEvento $estado)
    {
        $estado->update($request->all());
        return response()->json($estado);
    }

    /**
     * @OA\Delete(
     *     path="/estados-evento/{estado}",
     *     tags={"Estados de Evento"},
     *     summary="Eliminar un estado de evento",
     *     description="Elimina un estado de evento existente por su ID.",
     *     operationId="destroyEstEv",
     *     @OA\Parameter(
     *         name="estado",
     *         in="path",
     *         description="ID del estado de evento",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Estado de evento eliminado exitosamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Estado de evento no encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Estado de evento no encontrado"),
     *         )
     *     )
     * )
     */
    public function destroy(EstadoEvento $estado)
    {
        $estado->delete();
        return response()->json(null, 204);
    }
}

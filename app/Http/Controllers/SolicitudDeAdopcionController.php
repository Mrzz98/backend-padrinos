<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SolicitudDeAdopcion;

/**
 * @OA\Schema(
 *     schema="solicitudDeAdopcion",
 *     title="solicitudDeAdopcion",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="id_del_usuario", type="integer"),
 *     @OA\Property(property="id_del_adoptante", type="integer"),
 *     @OA\Property(property="id_del_animal", type="integer"),
 *     @OA\Property(property="fecha_solicitud", type="date"),
 *     @OA\Property(property="datos_adicionales", type="string"),
 *     @OA\Property(property="estado", type="boolean"),
 * )
 */
class SolicitudDeAdopcionController extends Controller
{
    /**
     * @OA\Get(
     *     path="/solicitudes",
     *     tags={"Solicitudes"},
     *     summary="Obtener todas las solicitudes de adopción",
     *     description="Recupera todas las solicitudes de adopción de la base de datos.",
     *     operationId="indexSolicitudes",
     *     @OA\Response(
     *         response=200,
     *         description="Lista de solicitudes de adopción",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/solicitudDeAdopcion")
     *         )
     *     )
     * )
     */
    public function index()
    {
        $solicitudes = SolicitudDeAdopcion::all();

        return response()->json($solicitudes, 200);
    }

    /**
     * @OA\Post(
     *     path="/solicitudes",
     *     tags={"Solicitudes"},
     *     summary="Registrar una nueva solicitud de adopción",
     *     description="Crear una nueva solicitud de adopción con la información proporcionada.",
     *     operationId="storeSolicitud",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos de la solicitud de adopción",
     *         @OA\JsonContent(
     *             required={"id_del_usuario", "id_del_adoptante", "id_del_animal", "fecha_solicitud", "estado"},
     *             @OA\Property(property="id_del_usuario", type="integer", format="int", example=1),
     *             @OA\Property(property="id_del_adoptante", type="integer", format="int", example=2),
     *             @OA\Property(property="id_del_animal", type="integer", format="int", example=3),
     *             @OA\Property(property="fecha_solicitud", type="date", example="2023-01-01"),
     *             @OA\Property(property="datos_adicionales", type="string", format="string", example="Información adicional"),
     *             @OA\Property(property="estado", type="boolean", format="boolean", example=true),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Solicitud de adopción registrada exitosamente",
     *         @OA\JsonContent(
     *             type="object",
     *             ref="#/components/schemas/solicitudDeAdopcion"
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
        // Validar los datos recibidos en la solicitud
        $request->validate([
            'id_del_usuario' => 'required|integer',
            'id_del_adoptante' => 'required|integer',
            'id_del_animal' => 'required|integer',
            'fecha_solicitud' => 'required|date',
            'datos_adicionales' => 'string',
            'estado' => 'required|boolean',
        ]);

        // Crear una nueva solicitud de adopción
        $solicitud = SolicitudDeAdopcion::create([
            'id_del_usuario' => $request->input('id_del_usuario'),
            'id_del_adoptante' => $request->input('id_del_adoptante'),
            'id_del_animal' => $request->input('id_del_animal'),
            'fecha_solicitud' => $request->input('fecha_solicitud'),
            'datos_adicionales' => $request->input('datos_adicionales'),
            'estado' => $request->input('estado'),
        ]);

        return response()->json($solicitud, 201);
    }

    /**
     * @OA\Get(
     *     path="/solicitudes/{id}",
     *     tags={"Solicitudes"},
     *     summary="Obtener información de una solicitud de adopción",
     *     description="Obtiene información de una solicitud de adopción por su ID.",
     *     operationId="showSolicitud",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la solicitud de adopción",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Información de la solicitud de adopción",
     *         @OA\JsonContent(
     *             type="object",
     *             ref="#/components/schemas/solicitudDeAdopcion"
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Solicitud de adopción no encontrada",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Solicitud de adopción no encontrada"),
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        // Buscar la solicitud de adopción por su ID en la base de datos
        $solicitud = SolicitudDeAdopcion::find($id);

        // Verificar si la solicitud de adopción existe
        if (!$solicitud) {
            return response()->json(['error' => 'Solicitud de adopción no encontrada'], 404);
        }

        return response()->json($solicitud, 200);
    }

    /**
     * @OA\Put(
     *     path="/solicitudes/{id}",
     *     tags={"Solicitudes"},
     *     summary="Actualizar una solicitud de adopción",
     *     description="Actualiza una solicitud de adopción existente por su ID con la información proporcionada.",
     *     operationId="updateSolicitud",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la solicitud de adopción",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos actualizados de la solicitud de adopción",
     *         @OA\JsonContent(
     *             required={"id_del_usuario", "id_del_adoptante", "id_del_animal", "fecha_solicitud", "estado"},
     *             @OA\Property(property="id_del_usuario", type="integer", format="int", example=1),
     *             @OA\Property(property="id_del_adoptante", type="integer", format="int", example=2),
     *             @OA\Property(property="id_del_animal", type="integer", format="int", example=3),
     *             @OA\Property(property="fecha_solicitud", type="date", example="2023-01-01"),
     *             @OA\Property(property="datos_adicionales", type="string", format="string", example="Información adicional actualizada"),
     *             @OA\Property(property="estado", type="boolean", format="boolean", example=true),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Solicitud de adopción actualizada exitosamente",
     *         @OA\JsonContent(
     *             type="object",
     *             ref="#/components/schemas/solicitudDeAdopcion"
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Solicitud de adopción no encontrada",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Solicitud de adopción no encontrada"),
     *         )
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $solicitud = SolicitudDeAdopcion::find($id);

        if (!$solicitud) {
            return response()->json(['error' => 'Solicitud de adopción no encontrada'], 404);
        }

        $request->validate([
            'id_del_usuario' => 'required|integer',
            'id_del_adoptante' => 'required|integer',
            'id_del_animal' => 'required|integer',
            'fecha_solicitud' => 'required|date',
            'datos_adicionales' => 'string',
            'estado' => 'required|boolean',
        ]);

        $solicitud->update([
            'id_del_usuario' => $request->input('id_del_usuario'),
            'id_del_adoptante' => $request->input('id_del_adoptante'),
            'id_del_animal' => $request->input('id_del_animal'),
            'fecha_solicitud' => $request->input('fecha_solicitud'),
            'datos_adicionales' => $request->input('datos_adicionales'),
            'estado' => $request->input('estado'),
        ]);

        return response()->json($solicitud, 200);
    }

    // Puedes agregar otras funciones según tus necesidades
}

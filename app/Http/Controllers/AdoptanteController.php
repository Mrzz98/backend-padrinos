<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Adoptante;

/**
 * @OA\Schema(
 *     schema="adoptante",
 *     title="adoptante",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="nombre", type="string"),
 *     @OA\Property(property="apellido", type="string"),
 *     @OA\Property(property="correo_electronico", type="string"),
 *     @OA\Property(property="telefono", type="string"),
 *     @OA\Property(property="direccion", type="string"),
 *     @OA\Property(property="ocupacion", type="string"),
 * )
 */
class AdoptanteController extends Controller
{
    /**
     * @OA\Get(
     *     path="/adoptantes",
     *     tags={"Adoptantes"},
     *     summary="Obtener todos los adoptantes",
     *     description="Recupera todos los adoptantes de la base de datos.",
     *     operationId="getAll",
     *     @OA\Response(
     *         response=200,
     *         description="Lista de adoptantes",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/adoptante")
     *         )
     *     )
     * )
     */
    public function getAll()
    {
        $adoptantes = Adoptante::all(); // Recupera todos los adoptantes de la base de datos

        return response()->json($adoptantes, 200);
    }

    /**
     * @OA\Post(
     *     path="/adoptantes",
     *     tags={"Adoptantes"},
     *     summary="Registrar un nuevo adoptante",
     *     description="Crear un nuevo adoptante con la información proporcionada.",
     *     operationId="store",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos del adoptante",
     *         @OA\JsonContent(
     *             required={"nombre", "apellido", "correo_electronico", "telefono", "direccion"},
     *             @OA\Property(property="nombre", type="string", format="string", example="Nombre"),
     *             @OA\Property(property="apellido", type="string", format="string", example="Apellido"),
     *             @OA\Property(property="correo_electronico", type="string", format="email", example="adoptante@ejemplo.com"),
     *             @OA\Property(property="telefono", type="string", format="string", example="123456789"),
     *             @OA\Property(property="direccion", type="string", format="string", example="Dirección de ejemplo"),
     *             @OA\Property(property="ocupacion", type="string", format="string", example="Ocupación de ejemplo"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Adoptante registrado exitosamente",
     *         @OA\JsonContent(
     *             type="object",
     *             ref="#/components/schemas/adoptante"
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
            'nombre' => 'required|string',
            'apellido' => 'required|string',
            'correo_electronico' => 'required|email',
            'telefono' => 'required|string',
            'direccion' => 'required|string',
            'ocupacion' => 'nullable|string',
        ]);

        // Crear un nuevo adoptante
        $adoptante = Adoptante::create($request->all());

        return response()->json($adoptante, 201);
    }

    /**
     * @OA\Get(
     *     path="/adoptantes/{id}",
     *     tags={"Adoptantes"},
     *     summary="Obtener información de un adoptante",
     *     description="Obtiene información de un adoptante por su ID.",
     *     operationId="show",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del adoptante",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Información del adoptante",
     *         @OA\JsonContent(
     *             type="object",
     *             ref="#/components/schemas/adoptante"
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Adoptante no encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Adoptante no encontrado"),
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        // Buscar el adoptante por su ID en la base de datos
        $adoptante = Adoptante::find($id);

        // Verificar si el adoptante existe
        if (!$adoptante) {
            return response()->json(['error' => 'Adoptante no encontrado'], 404);
        }

        return response()->json($adoptante, 200);
    }
}

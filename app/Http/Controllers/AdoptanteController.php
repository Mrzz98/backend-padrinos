<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Adoptante;
class AdoptanteController extends Controller
{
    /**
     * @OA\Get(
     *     path="/adoptantes",
     *     summary="Obtener todos los adoptantes",
     *     description="Obtiene la lista de todos los adoptantes en la base de datos",
     *     operationId="index",
     *     tags={"Adoptantes"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de adoptantes",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/adoptante")
     *         )
     *     ),
     *     security={
     *         {"api_key": {}}
     *     }
     * )
     */
    public function index()
    {
        $adoptantes = Adoptante::all(); // Recupera todos los adoptantes de la base de datos

        return response()->json($adoptantes, 200);
    }

    /**
     * @OA\Post(
     *     path="/adoptantes",
     *     summary="Crear un nuevo adoptante",
     *     description="Crea un nuevo adoptante en la base de datos",
     *     operationId="crearAdoptante",
     *     tags={"Adoptantes"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/adoptante")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Adoptante creado exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/adoptante")
     *     ),
     *     security={
     *         {"api_key": {}}
     *     }
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
     *     summary="Obtener un adoptante por ID",
     *     description="Recupera un adoptante específico por su ID",
     *     operationId="obtenerAdoptantePorId",
     *     tags={"Adoptantes"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del adoptante a recuperar",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Adoptante recuperado exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/adoptante")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Adoptante no encontrado"
     *     ),
     *     security={
     *         {"api_key": {}}
     *     }
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

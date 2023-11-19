<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\Rescate;
use Illuminate\Http\Request;

class RescateController extends Controller
{
    //
    /**
     * @OA\Get(
     *     path="/rescates",
     *     summary="Obtener todos los rescates",
     *     description="Obtiene la lista de todos los rescates en la base de datos",
     *     operationId="index",
     *     tags={"Rescates"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de rescates",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/rescate")
     *         )
     *     ),
     *     security={
     *         {"api_key": {}}
     *     }
     * )
     */
    public function index()
    {
        $rescates = Rescate::all();
        return response()->json($rescates, 200);
    }

    /**
     * @OA\Post(
     *     path="/rescates",
     *     summary="Crear un nuevo rescate",
     *     description="Crea un nuevo rescate en la base de datos",
     *     operationId="crearRescate",
     *     tags={"Rescates"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/rescate")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Rescate creado exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/rescate")
     *     ),
     *     security={
     *         {"api_key": {}}
     *     }
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_usuario' => 'required|exists:usuarios,id',
            'direccion' => 'required|string',
            'estado' => 'required|string',
            'fecha_rescate' => 'required|date',
            'informacion_adicional' => 'nullable|string',
            'id_animal' => 'required|exists:animales,id',
        ]);

        $rescate = Rescate::create($request->all());

        return response()->json($rescate, 201);
    }

    /**
     * @OA\Post(
     *     path="/rescates/store2",
     *     summary="Crear un nuevo rescate con creación de animal",
     *     description="Crea un nuevo rescate en la base de datos y crea un nuevo animal si no existe",
     *     operationId="crearRescateConAnimal",
     *     tags={"Rescates"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/rescateConAnimal")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Rescate creado exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/rescate")
     *     ),
     *     security={
     *         {"api_key": {}}
     *     }
     * )
     */
    public function store2(Request $request)
    {
        $request->validate([
            'id_usuario' => 'required|exists:usuarios,id',
            'direccion' => 'required|string',
            'estado' => 'required|string',
            'fecha_rescate' => 'required|date',
            'informacion_adicional' => 'nullable|string',
            'id_animal.nombre' => 'required|string',
            'id_animal.especie' => 'required|string',
            'id_animal.raza' => 'string',
            'id_animal.edad' => 'integer',
            'id_animal.descripcion' => 'string',
        ]);

        // Crear el animal
        $animal = Animal::create($request->input('id_animal'));

        // Actualizar la solicitud con el ID del animal creado
        $request->merge(['id_animal' => $animal->id]);

        // Crear el rescate
        $rescate = Rescate::create($request->all());

        return response()->json($rescate, 201);
    }

    /**
     * @OA\Get(
     *     path="/rescates/{id}",
     *     summary="Obtener un rescate por ID",
     *     description="Recupera un rescate específico por su ID",
     *     operationId="obtenerRescatePorId",
     *     tags={"Rescates"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del rescate a recuperar",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Rescate recuperado exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/rescate")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Rescate no encontrado"
     *     ),
     *     security={
     *         {"api_key": {}}
     *     }
     * )
     */
    public function show($id)
    {
        $rescate = Rescate::find($id);

        if (!$rescate) {
            return response()->json(['error' => 'Rescate no encontrado'], 404);
        }

        return response()->json($rescate, 200);
    }


    /**
     * @OA\Put(
     *     path="/rescates/{id}",
     *     summary="Actualizar un rescate por ID",
     *     description="Actualiza un rescate específico por su ID",
     *     operationId="actualizarRescatePorId",
     *     tags={"Rescates"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del rescate a actualizar",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/rescate")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Rescate actualizado exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/rescate")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Rescate no encontrado"
     *     ),
     *     security={
     *         {"api_key": {}}
     *     }
     * )
     */
    public function update(Request $request, $id)
    {
        // Buscar el rescate por su ID en la base de datos
        $rescate = Rescate::find($id);

        // Verificar si el rescate existe
        if (!$rescate) {
            return response()->json(['error' => 'Rescate no encontrado'], 404);
        }

        // Validar los datos recibidos en la solicitud
        $request->validate([
            'id_usuario' => 'sometimes|required|exists:usuarios,id',
            'estado' => 'sometimes|required|string',
            'direccion' => 'sometimes|required|string',
            'fecha_rescate' => 'sometimes|required|date',
            'informacion_adicional' => 'sometimes|nullable|string',
            'id_animal' => 'sometimes|required|exists:animales,id',
        ]);

        // Actualizar los campos del rescate con los datos recibidos
        $rescate->update($request->all());

        return response()->json($rescate, 200);
    }
}

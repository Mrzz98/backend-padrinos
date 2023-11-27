<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\Rescate;
use Illuminate\Http\Request;

/**
 * @OA\Schema(
 *     schema="rescate",
 *     title="rescate",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="id_usuario", type="integer"),
 *     @OA\Property(property="direccion", type="string"),
 *     @OA\Property(property="estado", type="string"),
 *     @OA\Property(property="fecha_rescate", type="string", format="date"),
 *     @OA\Property(property="informacion_adicional", type="string"),
 *     @OA\Property(property="id_animal", type="integer"),
 * )
 */
class RescateController extends Controller
{
    /**
     * @OA\Get(
     *     path="/rescates",
     *     tags={"Rescates"},
     *     summary="Obtener todos los rescates",
     *     description="Recupera todos los rescates de la base de datos.",
     *     operationId="indexRescates",
     *     @OA\Response(
     *         response=200,
     *         description="Lista de rescates",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/rescate")
     *         )
     *     )
     * )
     */
    public function index()
    {
        // $rescates = Rescate::all();
        // return response()->json($rescates, 200);
        $rescates = Rescate::with('animal')->get();

        return response()->json($rescates, 200);
    }

    /**
     * @OA\Post(
     *     path="/rescates",
     *     tags={"Rescates"},
     *     summary="Registrar un nuevo rescate",
     *     description="Crear un nuevo rescate con la información proporcionada.",
     *     operationId="storeResc",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos del rescate",
     *         @OA\JsonContent(
     *             required={"id_usuario", "direccion", "estado", "fecha_rescate", "id_animal"},
     *             @OA\Property(property="id_usuario", type="integer", format="int", example=1),
     *             @OA\Property(property="direccion", type="string", format="string", example="Dirección del rescate"),
     *             @OA\Property(property="estado", type="string", format="string", example="En curso"),
     *             @OA\Property(property="fecha_rescate", type="string", format="date", example="2023-11-19"),
     *             @OA\Property(property="informacion_adicional", type="string", format="string"),
     *             @OA\Property(property="id_animal", type="integer", format="int", example=1),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Rescate registrado exitosamente",
     *         @OA\JsonContent(
     *             type="object",
     *             ref="#/components/schemas/rescate"
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
        $request->validate([
            'id_usuario' => 'required|integer',
            'direccion' => 'required|string',
            'estado' => 'required|string',
            'fecha_rescate' => 'required|date',
            'informacion_adicional' => 'nullable|string',
            'id_animal' => 'required|integer',
        ]);

        $rescate = Rescate::create($request->all());

        return response()->json($rescate, 201);
    }

    /**
     * @OA\Post(
     *     path="/rescates/store2",
     *     tags={"Rescates"},
     *     summary="Registrar un nuevo rescate con información detallada del animal",
     *     description="Crear un nuevo rescate con información detallada del animal proporcionada.",
     *     operationId="store2resc",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos del rescate con información detallada del animal",
     *         @OA\JsonContent(
     *             required={"id_usuario", "direccion", "estado", "fecha_rescate", "id_animal"},
     *             @OA\Property(property="id_usuario", type="integer", format="int", example=1),
     *             @OA\Property(property="direccion", type="string", format="string", example="Dirección del rescate"),
     *             @OA\Property(property="estado", type="string", format="string", example="En curso"),
     *             @OA\Property(property="fecha_rescate", type="string", format="date", example="2023-11-19"),
     *             @OA\Property(property="informacion_adicional", type="string", format="string"),
     *             @OA\Property(property="id_animal", type="object", required={"nombre", "especie"}, 
     *                 @OA\Property(property="nombre", type="string", format="string", example="Nombre del animal"),
     *                 @OA\Property(property="especie", type="string", format="string", example="Especie del animal"),
     *                 @OA\Property(property="tamano", type="string", format="string", example="tamano del animal"),
     *                 @OA\Property(property="edad", type="integer", format="int", example=3),
     *                 @OA\Property(property="descripcion", type="string", format="string", example="Descripción del animal"),
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Rescate registrado exitosamente",
     *         @OA\JsonContent(
     *             type="object",
     *             ref="#/components/schemas/rescate"
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
    public function store2(Request $request)
    {
        $request->validate([
            'id_usuario' => 'required|integer',
            'direccion' => 'required|string',
            'estado' => 'required|string',
            'fecha_rescate' => 'required|date',
            'informacion_adicional' => 'nullable|string',
            'id_animal.nombre' => 'required|string',
            'id_animal.especie' => 'required|string',
            'id_animal.tamano' => 'string',
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
     *     tags={"Rescates"},
     *     summary="Obtener información de un rescate",
     *     description="Obtiene información de un rescate por su ID.",
     *     operationId="showResc",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del rescate",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Información del rescate",
     *         @OA\JsonContent(
     *             type="object",
     *             ref="#/components/schemas/rescate"
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Rescate no encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Rescate no encontrado"),
     *         )
     *     )
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
     *     tags={"Rescates"},
     *     summary="Actualizar un rescate",
     *     description="Actualizar la información de un rescate por su ID.",
     *     operationId="update",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del rescate",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos actualizados del rescate",
     *         @OA\JsonContent(
     *             required={"id_usuario", "direccion", "estado", "fecha_rescate", "id_animal"},
     *             @OA\Property(property="id_usuario", type="integer", format="int", example=1),
     *             @OA\Property(property="direccion", type="string", format="string", example="Nueva dirección del rescate"),
     *             @OA\Property(property="estado", type="string", format="string", example="Completado"),
     *             @OA\Property(property="fecha_rescate", type="string", format="date", example="2023-11-20"),
     *             @OA\Property(property="informacion_adicional", type="string", format="string", example="Nueva información adicional"),
     *             @OA\Property(property="id_animal", type="integer", format="int", example=2),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Rescate actualizado exitosamente",
     *         @OA\JsonContent(
     *             type="object",
     *             ref="#/components/schemas/rescate"
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Rescate no encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Rescate no encontrado"),
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

    /**
     * @OA\Delete(
     *     path="/rescates/{id}",
     *     tags={"Rescates"},
     *     summary="Eliminar un rescate",
     *     description="Elimina un rescate existente por su ID.",
     *     operationId="deleteRescate",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del rescate",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Rescate eliminado exitosamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Rescate no encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Rescate no encontrado"),
     *         )
     *     )
     * )
     */

    public function destroy($id)
    {
        $rescate = Rescate::find($id);

        if (!$rescate) {
            return response()->json(['error' => 'Movimiento de animal no encontrado'], 404);
        }

        $rescate->delete();

        return response()->json(null, 204);
    }


    /**
     * @OA\Put(
     *     path="/rescates-animal/{id}",
     *     tags={"Rescates"},
     *     summary="Actualizar un rescate con información detallada del animal",
     *     description="Actualizar un rescate existente con información detallada del animal proporcionada.",
     *     operationId="updateRescateAnimal",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del rescate",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos del rescate con información detallada del animal",
     *         @OA\JsonContent(
     *             @OA\Property(property="id_usuario", type="integer", format="int", example=1),
     *             @OA\Property(property="direccion", type="string", format="string", example="Nueva dirección del rescate"),
     *             @OA\Property(property="estado", type="string", format="string", example="En proceso"),
     *             @OA\Property(property="fecha_rescate", type="string", format="date", example="2023-11-20"),
     *             @OA\Property(property="informacion_adicional", type="string", format="string"),
     *             @OA\Property(
     *                 property="id_animal",
     *                 type="object",
     *                 required={"nombre", "especie", "tamano", "edad", "descripcion"},
     *                 @OA\Property(property="nombre", type="string", format="string", example="Nuevo nombre del animal"),
     *                 @OA\Property(property="especie", type="string", format="string", example="Nueva especie del animal"),
     *                 @OA\Property(property="tamano", type="string", format="string", example="Nuevo tamaño del animal"),
     *                 @OA\Property(property="edad", type="integer", format="int", example=4),
     *                 @OA\Property(property="descripcion", type="string", format="string", example="Nueva descripción del animal"),
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Rescate actualizado exitosamente",
     *         @OA\JsonContent(
     *             type="object",
     *             ref="#/components/schemas/rescate"
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Rescate no encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Rescate no encontrado"),
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
    public function updateWithAnimal(Request $request, $id)
    {
        $request->validate([
            'id_usuario' => 'sometimes|required|integer',
            'direccion' => 'sometimes|required|string',
            'estado' => 'sometimes|required|string',
            'fecha_rescate' => 'sometimes|required|date',
            'informacion_adicional' => 'sometimes|nullable|string',
            'id_animal.nombre' => 'sometimes|required|string',
            'id_animal.especie' => 'sometimes|required|string',
            'id_animal.tamano' => 'sometimes|string',
            'id_animal.edad' => 'sometimes|integer',
            'id_animal.descripcion' => 'sometimes|string',
        ]);

        $rescate = Rescate::find($id);

        if (!$rescate) {
            return response()->json(['error' => 'Rescate no encontrado'], 404);
        }

        // Actualizar el animal
        $rescate->animal->update($request->input('id_animal'));

        // Actualizar el rescate
        $rescate->update($request->all());

        return response()->json($rescate, 200);
    }
}

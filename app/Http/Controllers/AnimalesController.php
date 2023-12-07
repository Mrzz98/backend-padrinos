<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\PDF;
use App\Models\Animal;
use Illuminate\Support\Facades\File;

/**
 * @OA\Schema(
 *     schema="animal",
 *     title="animal",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="nombre", type="string"),
 *     @OA\Property(property="especie", type="string"),
 *     @OA\Property(property="tamano", type="string"),
 *     @OA\Property(property="edad", type="integer"),
 *     @OA\Property(property="descripcion", type="string"),
 * )
 */
class AnimalesController extends Controller
{
    /**
     * @OA\Get(
     *     path="/animales",
     *     tags={"Animales"},
     *     summary="Obtener todos los animales",
     *     description="Recupera todos los animales de la base de datos.",
     *     operationId="indexAnimales",
     *     @OA\Response(
     *         response=200,
     *         description="Lista de animales",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/animal")
     *         )
     *     )
     * )
     */
    public function index()
    {
        $animales = Animal::all();

        // Transformar cada animal para incluir la URL completa
        // $animalesTransformados = $animales->map(function ($animal) {
        //     // Agregar la URL completa a la propiedad imagen_path
        //     $animal->imagen_path = asset('images/' . $animal->imagen_path);

        //     return $animal;
        // });

        return response()->json($animales, 200);
    }

    /**
     * @OA\Post(
     *     path="/animales",
     *     tags={"Animales"},
     *     summary="Registrar un nuevo animal",
     *     description="Crear un nuevo animal con la información proporcionada.",
     *     operationId="storeAnimal",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos del animal",
     *         @OA\JsonContent(
     *             required={"nombre", "especie"},
     *             @OA\Property(property="nombre", type="string", format="string", example="Nombre"),
     *             @OA\Property(property="especie", type="string", format="string", example="Especie"),
     *             @OA\Property(property="tamano", type="string", format="string", example="tamano"),
     *             @OA\Property(property="edad", type="integer", format="int", example=3),
     *             @OA\Property(property="descripcion", type="string", format="string", example="Descripción del animal"),
     *             @OA\Property(property="image", type="string", format="string", example="STRING de Imagen en base64"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Animal registrado exitosamente",
     *         @OA\JsonContent(
     *             type="object",
     *             ref="#/components/schemas/animal"
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
            'especie' => 'required|string',
            'tamano' => 'string',
            'edad' => 'integer',
            'descripcion' => 'string',
            'image' => 'required|string'
        ]);

        $animal = Animal::create([
            'nombre' => $request->input('nombre'),
            'especie' => $request->input('especie'),
            'tamano' => $request->input('tamano'),
            'edad' => $request->input('edad'),
            'descripcion' => $request->input('descripcion'),
            'imagen_path' => $request->input('image'),
        ]);

        return response()->json($animal, 201);
    }

    /**
     * @OA\Get(
     *     path="/animales/{id}",
     *     tags={"Animales"},
     *     summary="Obtener información de un animal",
     *     description="Obtiene información de un animal por su ID.",
     *     operationId="showAnimal",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del animal",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Información del animal",
     *         @OA\JsonContent(
     *             type="object",
     *             ref="#/components/schemas/animal"
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Animal no encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Animal no encontrado"),
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        // Buscar el animal por su ID en la base de datos
        $animal = Animal::find($id);

        // Verificar si el animal existe
        if (!$animal) {
            return response()->json(['error' => 'Animal no encontrado'], 404);
        }

        return response()->json($animal, 200);
    }

    /**
     * @OA\Get(
     *     path="/generar-pdf-animales",
     *     tags={"Animales"},
     *     summary="Generar PDF de animales",
     *     description="Genera un archivo PDF que contiene la lista de todos los animales.",
     *     operationId="generarPDFAnimales",
     *     @OA\Response(
     *         response=200,
     *         description="PDF generado exitosamente",
     *     )
     * )
     */
    public function generarPDFAnimales()
    {
        $PDF = app('PDF');
        $animales = Animal::all(); // Obtén la lista de animales desde tu modelo Animal

        $data = ['animales' => $animales]; // Datos que deseas pasar a la vista

        $pdf = $PDF::loadView('reporte_animales', $data); // Asegúrate de tener una vista llamada 'reporte_animales'

        return $pdf->stream('reporte_animales.pdf');
    }


    /**
     * @OA\Put(
     *     path="/animales/{id}",
     *     tags={"Animales"},
     *     summary="Actualizar información de un animal",
     *     description="Actualizar la información de un animal existente.",
     *     operationId="updateAnimal",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del animal a actualizar",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Nuevos datos del animal",
     *         @OA\JsonContent(
     *             @OA\Property(property="nombre", type="string", format="string", example="Nuevo Nombre"),
     *             @OA\Property(property="especie", type="string", format="string", example="Nueva Especie"),
     *             @OA\Property(property="tamano", type="string", format="string", example="Nuevo tamaño"),
     *             @OA\Property(property="edad", type="integer", format="int", example=4),
     *             @OA\Property(property="descripcion", type="string", format="string", example="Nueva descripción del animal"),
     *             @OA\Property(property="image", type="string", format="string", example="Nueva STRING de Imagen en base64"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Animal actualizado exitosamente",
     *         @OA\JsonContent(
     *             type="object",
     *             ref="#/components/schemas/animal"
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Animal no encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Animal no encontrado"),
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
        // Validar los datos recibidos en la solicitud
        $request->validate([
            'nombre' => 'string',
            'especie' => 'string',
            'tamano' => 'string',
            'edad' => 'integer',
            'descripcion' => 'string',
            'image' => 'string',
        ]);

        // Buscar el animal por ID
        $animal = Animal::find($id);

        // Verificar si el animal existe
        if (!$animal) {
            return response()->json(['error' => 'Animal no encontrado'], 404);
        }

        // Actualizar los datos del animal
        $animal->update($request->all());

        return response()->json($animal, 200);
    }


    /**
     * @OA\Delete(
     *     path="/animales/{id}",
     *     tags={"Animales"},
     *     summary="Eliminar un animal",
     *     description="Elimina un animal por su ID.",
     *     operationId="destroyAnimal",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del animal a eliminar",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Animal eliminado exitosamente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Animal eliminado con éxito"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Animal no encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Animal no encontrado"),
     *         )
     *     )
     * )
     */
    public function destroy($id)
    {
        // Buscar el animal que se va a eliminar
        $animal = Animal::findOrFail($id);

        // Obtener la ruta de la imagen
        $imagePath = public_path('images/') . $animal->imagen_path;

        // Verificar si la imagen existe antes de intentar eliminarla
        if (File::exists($imagePath)) {
            // Eliminar la imagen
            File::delete($imagePath);
        }

        // Eliminar el animal de la base de datos
        $animal->delete();

        return response()->json(['message' => 'Animal eliminado con éxito']);
    }
}

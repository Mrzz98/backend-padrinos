<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\PDF;
use App\Models\Animal;

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
        $animales = Animal::all(); // Recupera todos los animales de la base de datos

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
            'imagen_path' => 'string'
        ]);

        // Crear un nuevo animal
        $animal = Animal::create([
            'nombre' => $request->input('nombre'),
            'especie' => $request->input('especie'),
            'tamano' => $request->input('tamano'),
            'edad' => $request->input('edad'),
            'descripcion' => $request->input('descripcion'),
            'imagen_path' => $request->input('imagen_path')
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
}

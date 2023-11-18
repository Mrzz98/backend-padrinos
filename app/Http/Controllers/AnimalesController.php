<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\PDF;
use App\Models\Animal;

class AnimalesController extends Controller
{
    /**
     * @OA\Get(
     *     path="/animales",
     *     summary="Obtener todos los animales",
     *     description="Obtiene la lista de todos los animales en la base de datos",
     *     operationId="index",
     *     tags={"Animales"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de animales",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/animal")
     *         )
     *     ),
     *     security={
     *         {"api_key": {}}
     *     }
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
     *     summary="Crear un nuevo animal",
     *     description="Crea un nuevo animal en la base de datos",
     *     operationId="crearAnimal",
     *     tags={"Animales"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/animal")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Animal creado exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/animal")
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
            'especie' => 'required|string',
            'raza' => 'string',
            'edad' => 'integer',
            'descripcion' => 'string',
        ]);

        // Crear un nuevo animal
        $animal = Animal::create([
            'nombre' => $request->input('nombre'),
            'especie' => $request->input('especie'),
            'raza' => $request->input('raza'),
            'edad' => $request->input('edad'),
            'descripcion' => $request->input('descripcion'),
        ]);

        return response()->json($animal, 201);
    }

    /**
     * @OA\Get(
     *     path="/animales/{id}",
     *     summary="Obtener un animal por ID",
     *     description="Recupera un animal específico por su ID",
     *     operationId="obtenerAnimalPorId",
     *     tags={"Animales"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del animal a recuperar",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Animal recuperado exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/animal")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Animal no encontrado"
     *     ),
     *     security={
     *         {"api_key": {}}
     *     }
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
     *     path="/generarPdfAnimales",
     *     summary="Generar PDF de animales",
     *     description="Genera un archivo PDF que contiene la lista de animales",
     *     operationId="generarPDFAnimales",
     *     tags={"Animales"},
     *     @OA\Response(
     *         response=200,
     *         description="PDF generado exitosamente"
     *     ),
     *     security={
     *         {"api_key": {}}
     *     }
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
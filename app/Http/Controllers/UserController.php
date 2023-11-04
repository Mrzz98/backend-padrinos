<?php

namespace App\Http\Controllers;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Usuario;

class UserController extends Controller
{
    /**
 * @OA\Get(
 *     path="/usuarios",
 *     summary="Obtener todos los usuarios",
 *     description="Obtiene la lista de todos los usuarios en la base de datos",
 *     operationId="index",
 *     tags={"Usuarios"},
 *     @OA\Response(
 *         response=200,
 *         description="Lista de usuarios",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/usuario")
 *         )
 *     ),
 *     security={
 *         {"api_key": {}}
 *     }
 * )
 */
    public function index()
    {
        $usuarios = Usuario::all(); // Recupera todos los usuarios de la base de datos

        return response()->json($usuarios, 200);
    }

    /**
 * @OA\Get(
 *     path="/generarPdf",
 *     summary="Generar PDF de usuarios",
 *     description="Genera un archivo PDF que contiene la lista de usuarios",
 *     operationId="generarPDF",
 *     tags={"Usuarios"},
 *     @OA\Response(
 *         response=200,
 *         description="PDF generado exitosamente"
 *     ),
 *     security={
 *         {"api_key": {}}
 *     }
 * )
 */
    public function generarPDF()
    {
        $PDF = app('PDF');
        $users = Usuario::all(); // Obtén la lista de usuarios desde tu modelo User

        $data = ['users' => $users]; // Datos que deseas pasar a la vista
    
        $pdf = $PDF::loadView('reporte', $data);
    
        return $pdf->stream('reporte.pdf');
    }
}


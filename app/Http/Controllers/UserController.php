<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use app\Http\Controllers\Controller;
// use app\Http\Models\Usuario; // Asegúrate de importar el modelo Usuario desde la ubicación correcta
use App\Usuario as Usuario;

class UserController extends Controller
{
    public function index()
    {
        $usuarios = Usuario::all(); // Recupera todos los usuarios de la base de datos

        return response()->json($usuarios, 200);
    }
}

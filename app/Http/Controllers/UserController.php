<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario; // Asegúrate de importar el modelo Usuario desde la ubicación correcta

class UserController extends Controller
{
    public function index()
    {
        $usuarios = Usuario::all(); // Recupera todos los usuarios de la base de datos

        return response()->json($usuarios, 200);
    }
}

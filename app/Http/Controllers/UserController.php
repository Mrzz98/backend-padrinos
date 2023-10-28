<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Usuario;

class UserController extends Controller
{
    public function index()
    {
        $usuarios = Usuario::all(); // Recupera todos los usuarios de la base de datos

        return response()->json($usuarios, 200);
    }
}


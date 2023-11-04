<?php

namespace App\Http\Controllers;
use Barryvdh\DomPDF\PDF;
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

    public function generarPDF()
    {
        $users = Usuario::all(); // Obtén la lista de usuarios desde tu modelo User

        $data = ['users' => $users]; // Datos que deseas pasar a la vista
    
        $pdf = PDF::loadView('reporte', $data);
    
        return $pdf->stream('reporte.pdf');
    }
}


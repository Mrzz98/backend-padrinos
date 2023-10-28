<?php
namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        $credentials = $request->only('nombre_usuario', 'contrasena');
    
        if (Auth::attempt($credentials)) {
            return response()->json(['error' => 'aaaa incorrectas'], 401);
            // // Obtén el usuario autenticado como instancia de tu modelo Usuario
            // $user = Usuario::find(Auth::user()->id);
    
            // // Genera un token de acceso personalizado
            // $token = Str::random(60);
    
            // // Asocia el token al usuario y guárdalo en la base de datos
            // $user->api_token = $token;
            // $user->save();
    
            // return response()->json(['user' => $user, 'token' => $token], 200);
        }
    
        return response()->json(['error' => 'Credenciales incorrectas'], 401);
    }
}
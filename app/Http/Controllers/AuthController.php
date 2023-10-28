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
            $user = Auth::user();

            // Genera un token de acceso personalizado
            $token = Str::random(60); // Genera una cadena aleatoria para el token

            // Asocia el token al usuario (puedes guardar esto en la base de datos si lo deseas)
            $user->api_token = $token;
            // $user->save();

            return response()->json(['user' => $user, 'token' => $token], 200);
        }

        return response()->json(['error' => 'Credenciales incorrectas'], 401);
    }
}
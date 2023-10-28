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
        $jsonData = $request->json()->all();

        try {
            // Busca al usuario por su nombre de usuario
            $user = Usuario::where('nombre_usuario', $jsonData['nombre_usuario'])->first();

            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Usuario no encontrado',
                ], 401);
            }

            // Verifica las credenciales y genera el token
            $token = auth()->attempt([
                'nombre_usuario' => $jsonData['nombre_usuario'],
                'password' => $jsonData['contrasena'],
            ]);

            if (!$token) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Credenciales incorrectas',
                ], 401);
            }

            return response()->json([
                'status' => 'ok',
                'message' => 'Inicio de sesión exitoso',
                'user' => $token,
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage(),
            ], 500);
        }
    }
}
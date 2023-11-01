<?php
namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    // public function login(Request $request)
    // {
    //     $jsonData = $request->json()->all();

    //     try {
    //         // Busca al usuario por su nombre de usuario
    //         $user = Usuario::where('nombre_usuario', $jsonData['nombre_usuario'])->first();

    //         if (!$user) {
    //             return response()->json([
    //                 'status' => 'error',
    //                 'message' => 'Usuario no encontrado',
    //             ], 401);
    //         }

    //         // Verifica las credenciales y genera el token
    //         $token = auth()->attempt([
    //             'nombre_usuario' => $jsonData['nombre_usuario'],
    //             'password' => $jsonData['contrasena'],
    //         ]);

    //         if (!$token) {
    //             return response()->json([
    //                 'status' => 'error',
    //                 'message' => 'Credenciales incorrectas',
    //             ], 401);
    //         }

    //         return response()->json([
    //             'status' => 'ok',
    //             'message' => 'Inicio de sesión exitoso',
    //             'user' => $token,
    //         ]);
    //     } catch (\Exception $exception) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => $exception->getMessage(),
    //         ], 500);
    //     }
    // }

    public function login(Request $request)
    {
        $credentials = $request->only('nombre_usuario', 'contrasena');

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Credenciales inválidas'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => $e], 500);
        }

        return response()->json(compact('token'));
    }

    // public function getAuthenticatedUser()
    // {
    //     try {
    //         if (!$user = JWTAuth::parseToken()->authenticate()) {
    //                 return response()->json(['user_not_found'], 404);
    //         }
    //         } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
    //                 return response()->json(['token_expired'], $e->getStatusCode());
    //         } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
    //                 return response()->json(['token_invalid'], $e->getStatusCode());
    //         } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
    //                 return response()->json(['token_absent'], $e->getStatusCode());
    //         }
    //         return response()->json(compact('user'));
    // }
}
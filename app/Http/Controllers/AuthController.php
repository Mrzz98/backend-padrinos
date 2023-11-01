<?php
namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth as FacadesJWTAuth;
// use Tymon\JWTAuth\JWTAuth as JWTAuth;

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

        public function __construct()
        {
            $this->middleware('auth:api', ['except' => ['login', 'register']]);//login, register methods won't go through the api guard
        }

        public function login(Request $request)
        {
            $validator = Validator::make($request->all(), [
                'nombre_usuario' => 'required',
                'contrasena' => 'required',
            ]);
            

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }
            // $jwtCredentials = [
            //     'nombre_usuario' => $request->nombre_usuario,
            //     'password' => $request->contrasena,
            // ];
            $jwtCredentials = $validator->validated();
            // $jwtAuth = JWTAuth::getFacadeRoot();
            $jwtAuth = app('JWTAuth');
            if (! $token = $jwtAuth::attempt($jwtCredentials)) {
                return response()->json(['error' => 'Credenciales incorrectas'], 401);
            }

            return response()->json(compact('token'));
        }

        public function register(Request $request)
        {
            $validator = Validator::make($request->all(), [
                'nombre' => 'required|string|between:2,100',
                'nombre_usuario' => 'required|string|max:100|unique:usuario',
                'contrasena' => 'required|string|confirmed|min:6',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $user = Usuario::create([
                'nombre' => $request->get('nombre'),
                'nombre_usuario' => $request->get('nombre_usuario'),
                'contrasena' => Hash::make($request->get('contrasena')),
            ]);

            $token = FacadesJWTAuth::fromUser($user);

            return response()->json([
                'message' => 'Usuario registrado exitosamente',
                'user' => $user,
                'token' => $token,
            ], 200);
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
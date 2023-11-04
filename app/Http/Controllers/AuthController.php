<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException as JWTException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException as TokenInvalidException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException as TokenExpiredException;
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
        $this->middleware('auth:api', ['except' => ['login', 'register']]); //login, register methods won't go through the api guard
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
        $data = $validator->validated();
        if (isset($data['contrasena'])) {
            $data['password'] = $data['contrasena']; // Cambia el nombre del atributo
            unset($data['contrasena']); // Elimina el atributo anterior si es necesario
        }

        $jwtCredentials = $data;

        $jwtAuth = app('JWTAuth');
        if (!$token = $jwtAuth::attempt($jwtCredentials)) {
            return response()->json(['error' => 'Credenciales incorrectas'], 401);
        }

        // return response()->json(compact('token'));
        return $this->respondWithToken($token);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|between:2,100',
            'nombre_usuario' => 'required|string|max:100|unique:usuario',
            'contrasena' => 'required|string|confirmed|min:6',
            'apellido' => 'required|string|between:2,100',
            'correo_electronico' => 'required|email',
            'rol' => 'required|string|between:1,100'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = Usuario::create([
            'nombre' => $request->get('nombre'),
            'nombre_usuario' => $request->get('nombre_usuario'),
            'contrasena' => Hash::make($request->get('contrasena')),
            'apellido' => $request->get('apellido'),
            'correo_electronico' => $request->get('correo_electronico'),
            'rol' => $request->get('rol'),
        ]);

        $token = FacadesJWTAuth::fromUser($user);

        return response()->json([
            'message' => 'Usuario registrado exitosamente',
            'user' => $user,
            'token' => $token,
        ], 200);
    }

    public function getaccount()
    {
        $jwtAuth = app('JWTAuth');
        try {
            if (!$user = $jwtAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }
        } catch (TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getCode());
        } catch (TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getCode());
        } catch (JWTException $e) {
            return response()->json(['token_absent'], $e->getCode());
        }
        return response()->json(compact('user'));
    }

    public function logout()
    {
        $jwtAuth = app('JWTAuth');
        $jwtAuth->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        $jwtAuth = app('JWTAuth');
        return $this->respondWithToken($jwtAuth()->refresh());
    }
    protected function respondWithToken($token)
    {
        $jwtAuth = app('JWTAuth');
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => config('auth.guards.api.ttl') * 60 //mention the guard name inside the auth fn
        ]);
    }
}

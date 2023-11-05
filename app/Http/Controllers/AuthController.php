<?php

namespace App\Http\Controllers;


/**
 * @OA\Info(
 *     title="Deni",
 *     version="1.0",
 *     description="Descripción de la API de Ejemplo",
 *     termsOfService="https://www.ejemplo.com/terms",
 *     @OA\Contact(
 *         email="contacto@ejemplo.com"
 *     ),
 *     @OA\License(
 *         name="Licencia de Ejemplo",
 *         url="https://www.ejemplo.com/licencia"
 *     )
 * )
 */

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
    /**
     * @OA\Schema(
     *     schema="usuario",
     *     title="usuario",
     *     @OA\Property(property="id", type="integer"),
     *     @OA\Property(property="apellido", type="string"),
     *     @OA\Property(property="nombre_usuario", type="string"),
     *     @OA\Property(property="contrasena", type="string"),
     *     @OA\Property(property="correo_electronico", type="string"),
     *     @OA\Property(property="rol", type="string"),
     * )
     * * )
     */

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

    /**
     * @OA\Post(
     *     path="/login",
     *     tags={"Autenticación"},
     *     summary="Iniciar sesión",
     *     description="Iniciar sesión en la aplicación.",
     *     operationId="login",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Credenciales de inicio de sesión",
     *         @OA\JsonContent(
     *             required={"nombre_usuario", "contrasena"},
     *             @OA\Property(property="nombre_usuario", type="string", format="string", example="usuarioejemplo"),
     *             @OA\Property(property="contrasena", type="string", format="password", example="contrasenaejemplo"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Inicio de sesión exitoso",
     *         @OA\JsonContent(
     *             @OA\Property(property="access_token", type="string", format="string"),
     *             @OA\Property(property="token_type", type="string", format="string"),
     *             @OA\Property(property="expires_in", type="integer", format="int"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Credenciales incorrectas",
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validación fallida",
     *         @OA\JsonContent(
     *             @OA\Property(property="nombre_usuario", type="array", @OA\Items(type="string")),
     *             @OA\Property(property="contrasena", type="array", @OA\Items(type="string")),
     *         )
     *     )
     * )
     */

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

        $usuario = Usuario::where('nombre_usuario', $data['nombre_usuario'])->first();


        if (!$usuario) {
            return response()->json(['error' => 'Credenciales incorrecstas'], 401);
        }else{
            return response()->json(['a' => $usuario->contrasena, 
            'b' => $data['contrasena']
        ], 200);
        }

        // Compara la contraseña encriptada enviada por el cliente con la contraseña encriptada almacenada en la base de datos
        if ($data['contrasena'] === $usuario->contrasena) {
            // Las contraseñas coinciden
            $jwtAuth = app('JWTAuth');
            $jwtCredentials = [
                'nombre_usuario' => $data['nombre_usuario'],
                'password' => $data['contrasena'], // La contraseña ya está encriptada
            ];

            if (!$token = $jwtAuth::attempt($jwtCredentials)) {
                return response()->json(['error' => 'Credenciales incorreactas'], 401);
            }

            return $this->respondWithToken($token);
        } else {
            return response()->json(['error' => 'Credenciales incorrectas'], 401);
        }
    }

    /**
     * @OA\Post(
     *     path="/register",
     *     tags={"Registro"},
     *     summary="Registrarse",
     *     description="Registrar una nueva cuenta de usuario.",
     *     operationId="register",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos de registro de usuario",
     *         @OA\JsonContent(
     *             required={"nombre", "nombre_usuario", "contrasena", "contrasena_confirmation", "apellido", "correo_electronico", "rol"},
     *             @OA\Property(property="nombre", type="string", format="string", example="Nombre"),
     *             @OA\Property(property="nombre_usuario", type="string", format="string", example="usuarioejemplo"),
     *             @OA\Property(property="contrasena", type="string", format="password", example="contrasenaejemplo"),
     *             @OA\Property(property="contrasena_confirmation", type="string", format="password", example="contrasenaejemplo"),
     *             @OA\Property(property="apellido", type="string", format="string", example="Apellido"),
     *             @OA\Property(property="correo_electronico", type="string", format="email", example="usuario@ejemplo.com"),
     *             @OA\Property(property="rol", type="string", format="string", example="Rol"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuario registrado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", format="string"),
     *             @OA\Property(property="usuario", type="object", ref="#/components/schemas/usuario"),
     *             @OA\Property(property="token", type="string", format="string"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error en el registro",
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validación fallida",
     *         @OA\JsonContent(
     *             @OA\Property(property="nombre", type="array", @OA\Items(type="string")),
     *             @OA\Property(property="nombre_usuario", type="array", @OA\Items(type="string")),
     *             @OA\Property(property="contrasena", type="array", @OA\Items(type="string")),
     *             @OA\Property(property="apellido", type="array", @OA\Items(type="string")),
     *             @OA\Property(property="correo_electronico", type="array", @OA\Items(type="string")),
     *             @OA\Property(property="rol", type="array", @OA\Items(type="string")),
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/getaccount",
     *     tags={"usuario"},
     *     summary="Obtener información del usuario autenticado",
     *     description="Obtiene información del usuario que ha iniciado sesión.",
     *     operationId="getaccount",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Información del usuario",
     *         @OA\JsonContent(
     *             type="object",
     *             ref="#/components/schemas/usuario"
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado",
     *     )
     * )
     */
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


    /**
     * @OA\Post(
     *     path="/logout",
     *     tags={"Autenticación"},
     *     summary="Cerrar sesión",
     *     description="Cerrar sesión del usuario autenticado.",
     *     operationId="logout",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Sesión cerrada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", format="string", example="Sesión cerrada exitosamente"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado",
     *     )
     * )
     */
    public function logout()
    {
        auth()->logout();

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

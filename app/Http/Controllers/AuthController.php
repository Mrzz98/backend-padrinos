<?php
use Illuminate\Http\Request;
use app\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Valida los datos de inicio de sesión
        $credentials = $request->only('email', 'password');

        if (auth()->attempt($credentials)) {
            $user = auth()->user();
            $token = $user->createToken('MyApp')->accessToken;

            return response()->json(['token' => $token], 200);
        } else {
            return response()->json(['error' => 'Credenciales incorrectas'], 401);
        }
    }
}

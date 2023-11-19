<?php

/**
 * @OA\Info(
 *     title="Deni de Ejemplo",
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

use App\Http\Controllers\AnimalController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AnimalesController;
use App\Http\Controllers\EstadoEventoController;
use App\Http\Controllers\AdoptanteController;
use App\Http\Controllers\RescateController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware(['api'])->group(function() {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::get('/getaccount', [AuthController::class, 'getaccount']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/animales', [AnimalesController::class, 'index']);
    Route::post('/animales', [AnimalesController::class, 'store']);
    Route::get('/animales/{id}', [AnimalesController::class, 'show']);
    Route::get('/animales/report/pdf', [AnimalesController::class, 'generarPDFAnimales']);
    
});

Route::get('/adoptantes', [AdoptanteController::class, 'index']);
Route::post('/adoptantes', [AdoptanteController::class, 'store']);
Route::get('/adoptantes/{id}', [AdoptanteController::class, 'show']);

Route::get('/rescates', [RescateController::class, 'index']);
Route::post('/rescates', [RescateController::class, 'store']);
Route::post('/rescatesA', [RescateController::class, 'store2']);
Route::put('/rescates', [RescateController::class, 'udpate']);
Route::get('/rescates/{id}', [RescateController::class, 'show']);

// Obtener todos los estados de eventos
Route::get('/estadoEvento', [EstadoEventoController::class, 'index']);
// Crear un nuevo estado de evento
Route::post('/estadoEvento', [EstadoEventoController::class, 'store']);
// Mostrar un estado de evento específico
Route::get('/estadoEvento/{estado}', [EstadoEventoController::class, 'show']);
// Actualizar un estado de evento específico
Route::put('/estadoEvento/{estado}', [EstadoEventoController::class, 'update']);
// Eliminar un estado de evento específico
Route::delete('/estadoEvento/{estado}', [EstadoEventoController::class, 'destroy']);

Route::get('/usuarios/report/pdf', [UserController::class, 'generarPDF']);

Route::post('/logout', [AuthController::class, 'logout']);
Route::get('/usuarios', [UserController::class, 'index']);

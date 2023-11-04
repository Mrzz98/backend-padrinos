<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EstadoEventoController;

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
    
});
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

Route::get('/generarPdf', [UserController::class, 'generarPDF']);


Route::get('/usuarios', [UserController::class, 'index']);
// Route::post('/login', [AuthController::class, 'login']);

// Route::post('/logout', 'AuthController@logout');

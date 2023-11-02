<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

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
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::middleware(['auth:api'])->get('/getaccount', [AuthController::class, 'getaccount']);
    // Route::get('/getaccount', [AuthController::class, 'getaccount']);
});
Route::get('/usuarios', [UserController::class, 'index']);
// Route::post('/login', [AuthController::class, 'login']);

// Route::post('/logout', 'AuthController@logout');

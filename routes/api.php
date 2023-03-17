<?php

use App\Http\Controllers\Api\CategoriaController;
use App\Http\Controllers\Api\ProductoController;
use App\Http\Controllers\Api\FotoController;
use App\Http\Controllers\SanctumAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('producto', ProductoController::class);
Route::apiResource('imagen', FotoController::class);
Route::apiResource('categoria', CategoriaController::class);

// SANCTUM
Route::post('login', [SanctumAuthController::class, 'login']);
Route::post('register', [SanctumAuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('perfil', [SanctumAuthController::class, 'perfil']);
    Route::post('logout', [SanctumAuthController::class, 'logout']);
});

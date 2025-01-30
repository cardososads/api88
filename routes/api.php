<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'message' => 'Você não está logado.'
    ]);
});

Route::prefix('v1')->group(function () {

    // Rotas públicas
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    // Rotas protegidas por Sanctum
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);

        // Teste de permissão
        Route::get('teste', function () {
            return ['message' => 'Você está autenticado!'];
        })->middleware('permission:create_trainings');

        // CRUD de usuários
        Route::apiResource('users', UserController::class);

        // CRUD de Grupos
        Route::apiResource('groups', GroupController::class);

        // CRUD permissions
        Route::apiResource('permissions', PermissionController::class);
    });
});

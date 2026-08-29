<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// rota publica
Route::post('/login', [AuthController::class, 'login']);

// rota protegida
Route::middleware('auth:sanctum')->group(function () {
    
    // autenticação
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/novo', [AuthController::class, 'novoUser']);
    Route::put('/editUser/{user}', [AuthController::class, 'atualizar']);
    Route::get('/usuarios', [AuthController::class, 'usuarios']);
    Route::delete('/users/{user}', [AuthController::class, 'destroy']);
    Route::post('/logout', [AuthController::class, 'logout']);
});


<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TrufasController;
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

    // rotas de usuários
    Route::post('/novo', [AuthController::class, 'novoUser']);
    Route::put('/editUser/{user}', [AuthController::class, 'atualizar']);
    Route::get('/usuarios', [AuthController::class, 'usuarios']);
    Route::delete('/users/{user}', [AuthController::class, 'destroy']);

    // rotas de trufas
    Route::get('/trufas', [TrufasController::class, 'trufas']);
    Route::post('/novaTrufa', [TrufasController::class, 'novaTrufa']);
    Route::put('/editTrufa/{trufa}', [TrufasController::class, 'atualizar']);
    Route::delete('/trufa/{trufa}', [TrufasController::class, 'destroy']);

    // Saída
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::get('/trufas', [TrufasController::class, 'trufas']);
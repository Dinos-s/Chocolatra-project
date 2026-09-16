<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\SaboresController;
use App\Http\Controllers\TrufasController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// rota publica
Route::post('/login', [AuthController::class, 'login']);
Route::post('/registro', [AuthController::class, 'registrarCliente']);
Route::get('/trufas', [TrufasController::class, 'trufas']);

// rota protegida para qualquer usuário
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', fn (Request $request) => $request->user());
    Route::post('/logout', [AuthController::class, 'logout']); 

    // rotas de pedido
    Route::post('/pedidos', [PedidoController::class, 'store']);
    Route::get('/pedidos/{pedido}', [PedidoController::class, 'show']);
    Route::get('/meus-pedidos', [PedidoController::class, 'meusPedidos']);
});

// rota protegida apenas para admin
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    
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
    // Route::get('/trufas', [TrufasController::class, 'trufas']);
    Route::post('/novaTrufa', [TrufasController::class, 'novaTrufa']);
    Route::put('/editTrufa/{trufa}', [TrufasController::class, 'atualizar']);
    Route::delete('/trufa/{trufa}', [TrufasController::class, 'destroy']);

    // rotas de sabores
    Route::get('/sabores', [SaboresController::class, 'sabores']);
    Route::post('/novoSabor', [SaboresController::class, 'novoSabor']);
    Route::put('/editSabor/{sabor}', [SaboresController::class, 'atualizar']);
    Route::delete('/sabor/{sabor}', [SaboresController::class, 'destroy']);

    // rotas pedidos
    // Route::post('/pedidos', [PedidoController::class, 'store']);
    // Route::get('/pedidos/{pedido}', [PedidoController::class, 'show']);
    Route::post('/pedidos/{pedido}/pagar', [PedidoController::class, 'confirmarPagamento'])->name('pedidos.confirmar');
});
<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function registrarCliente(Request $request): JsonResponse {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed|min:8',
            'cpf' => 'required|string|unique:users,cpf',
            'phone' => 'required|string'
        ]);

        $user = Cliente::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'cpf' => $request->cpf,
            'phone' => $request->phone
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Cadastro realizado com sucesso',
            'user' => $user,
            'token' => $token
        ], 201);
    }

    // public function logout(Request $request): JsonResponse {
    //     $request->user()->currentAccessToken()->delete();

    //     return response()->json([
    //         'message' => 'Logout realizado com sucesso'
    //     ], 200);
    // }
}
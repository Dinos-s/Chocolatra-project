<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse {
        $data = $request->validate([
           'email' => 'required|email',
           'password' => 'required'
        ]);

        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json([
                'message' => 'Credenciais inválidas'
            ], 401);
        }
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login realizado com sucesso',
            'user' => $user,
            'token' => $token
        ]);
    }

    public function logout(Request $request): JsonResponse {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout realizado com sucesso'
        ]);
    }

    public function novoUser(Request $request): JsonResponse {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'success',
            'user' => $user,
            'token' => $token
        ], 201);
    }

    public function usuarios(): JsonResponse 
    {
        $users = User::orderBy('name', 'asc')->paginate(10);

        return response()->json([
            'status' => true,
            'users' => $users
        ], 200);
    }

    public function atualizar(Request $request, User $user): JsonResponse {

        $usuario = User::find($user->id);

        if (!$usuario) {
            return response()->json([
                'message' => 'Usuário não encontrado'
            ], 404);
        }
        
        $atualizar = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'cpf' => $request->cpf,
        ];

        if ($request->password) {
            $atualizar['password'] = bcrypt($request->password);
        }

        $user->update($atualizar);

        return response()->json([
            'message' => 'success'
        ]);
    }

    public function destroy(User $user): JsonResponse
    {
        try {
            $user->delete();

            return response()->json([
                'status' => true,
                'user' => $user,
                'message' => 'Usuário deletado com sucesso'
            ], 200);

        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => 'falha ao deletar o usuário'
            ], 400);
        }
    }
}

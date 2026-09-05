<?php 

namespace App\Http\Controllers;

use App\Models\Trufa;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TrufasController extends Controller
{
    public function trufas(): JsonResponse
    {
        $trufas = Trufa::orderBy('created_at', 'desc')->paginate(10);

        return response()->json([
            'status' => true,
            'trufas' => $trufas
        ], 200);
    }

    public function novaTrufa(Request $resquest): JsonResponse
    {
        $resquest->validate([
            'sabor' => 'required|string|max:255',
            'quantidade' => 'required|integer|max:255',
            // 'preco' => 'required|numeric|min:0',
        ]);

        $trufa = Trufa::create([
            'sabor' => $resquest->sabor,
            'quantidade' => $resquest->quantidade,
            // 'preco' => $resquest->preco
        ]);

        return response()->json([
            'message' => 'success',
            'trufa' => $trufa
        ], 201);
    }

    public function atualizar(Request $request,Trufa $trufa): JsonResponse
    {
        $trufa = Trufa::find($trufa->id);

        if (!$trufa) {
            return response()->json([
                'message' => 'Trufa não encontrada'
            ], 404);
        }
        
        $atualizar = [
            'sabor' => $request->sabor,
            'quantidade' => $request->quantidade,
            // 'preco' => $request->preco,
        ];

        $trufa->update($atualizar);

        return response()->json([
            'message' => 'success'
        ], 200);
    }

    public function destroy(Trufa $trufa): JsonResponse
    {
        try {
            $trufa->delete();

            return response()->json([
                'status' => true,
                'trufa' => $trufa,
                'message' => 'Trufa deletada com sucesso'
            ], 200);

        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => 'falha ao deletar a trufa'
            ], 400);
        }
    }
}
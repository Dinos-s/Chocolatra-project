<?php 

namespace App\Http\Controllers;

use App\Models\Sabor;
use App\Models\Trufa;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TrufasController extends Controller
{
    public function trufas(): JsonResponse
    {
        // $trufas = Trufa::orderBy('created_at', 'desc')->paginate(10);
        $trufas = Trufa::query()
        ->select('trufas.*', 'sabor_trufas.sabor')
        ->join('sabor_trufas', 'sabor_trufas.id', '=', 'trufas.id_sabor')
        ->orderBy('trufas.created_at', 'desc')
        ->paginate(10);

        $sabores = Sabor::orderBy('sabor', 'asc')->get();

        return response()->json([
            'status' => true,
            'trufas' => $trufas,
            'sabores' => $sabores
        ], 200);
    }

    public function sabores(): JsonResponse
    {
        $sabores = Sabor::orderBy('sabor', 'asc')->paginate(10);

        return response()->json([
            'status' => true,
            'sabores' => $sabores
        ], 200);
    }

    public function sabor(Request $request): JsonResponse
    {
        $request->validate([
            'sabor' => 'required|string'
        ]);

        $sabor = Sabor::create(['sabor' => $request->sabor]);

        return response()->json([
            'status' => true,
            'sabor' => $sabor
        ], 200);
    }

    public function novaTrufa(Request $resquest): JsonResponse
    {
        $resquest->validate([
            'id_sabor' => 'required|integer',
            'quantidade' => 'required|integer',
        ]);

        $sabor = Sabor::find($resquest->id_sabor);

        if (!$sabor) {
            return response()->json([
                'message' => 'Sabor não encontrado'
            ], 404);
        }

        $trufa = Trufa::create([
            'id_sabor' => $resquest->id_sabor,
            'quantidade' => $resquest->quantidade,
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
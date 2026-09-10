<?php 

namespace App\Http\Controllers;

use App\Models\Sabor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SaboresController extends Controller {

    public function sabores(): JsonResponse
    {
        $sabores = Sabor::orderBy('sabor', 'asc')->paginate(10);

        return response()->json([
            'status' => true,
            'sabores' => $sabores
        ], 200);
    }

    public function novoSabor(Request $request): JsonResponse
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

    public function atualizar(Request $request, Sabor $sabor): JsonResponse
    {
        $sabor = Sabor::find($sabor->id);

        if (!$sabor) {
            return response()->json([
                'message' => 'Sabor não encontrado'
            ], 404);
        }

        $atualizar = [
            'sabor' => $request->sabor,
        ];

        $sabor->update($atualizar);

        return response()->json([
            'message' => 'success'
        ], 200);
    }

    public function destroy(Sabor $sabor): JsonResponse
    {
        try {
            $sabor->delete();

            return response()->json([
                'status' => true,
                'sabor' => $sabor,
                'message' => 'Sabor deletado com sucesso'
            ], 200);

        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => 'falha ao deletar o sabor'
            ], 400);
        }
    }
}
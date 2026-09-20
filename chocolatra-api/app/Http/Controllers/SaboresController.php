<?php 

namespace App\Http\Controllers;

use App\Models\Sabor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SaboresController extends Controller {

    public function sabores(): JsonResponse
    {
        $sabores = Sabor::orderBy('sabor', 'asc')->paginate(10);
        // dd($sabores);
        return response()->json([
            'status' => true,
            'sabores' => $sabores
        ], 200);
    }

    public function novoSabor(Request $request): JsonResponse
    {
        $request->validate([
            'sabor' => 'required|string|max:255',
            'preco' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = $image->getClientOriginalName();
            $image->move(public_path('images/sabores'), $imageName);
        }

        $sabor = Sabor::create([
            'sabor' => $request->sabor,
            'preco' => $request->preco,
            'image' => $imageName
        ]);

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

        $request->validate([
            'sabor' => 'required|string|max:255',
            'preco' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            
            // Remove a imagem antiga
            if ($sabor->image) {
                $oldImage = public_path('images/sabores/' . $sabor->image);

                if (file_exists($oldImage)) {
                    unlink($oldImage);
                }
            }

            // Nome original
            $imageName = $image->getClientOriginalName();

            // Salva a nova imagem
            $image->move(
                public_path('images/sabores'),
                $imageName
            );
        }

        $atualizar = [
            'sabor' => $request->sabor,
            'preco' => $request->preco,
            'image' => $imageName
        ];

        $sabor->update($atualizar);

        return response()->json([
            'message' => 'success'
        ], 200);
    }

    public function destroy(Sabor $sabor): JsonResponse
    {
        try {
            $caminho = public_path('images/sabores/' . $sabor->image);

            if ($sabor->image && file_exists($caminho)) {
                unlink($caminho);
            }

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
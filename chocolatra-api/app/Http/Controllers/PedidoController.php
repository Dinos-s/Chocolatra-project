<?php

namespace App\Http\Controllers;

use App\Models\Estoque;
use App\Models\Pedido;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PedidoController extends Controller
{
    // cria o pedido e devolve o qr
    public function store(Request $request): JsonResponse
    {
        $request->validate([
           'itens' => 'required|array|min:1',
           'itens.*.id_sabor' => 'required|integer|exists:sabor_trufas,id',
           'itens.*.quantidade' => 'required|integer|min:1',
        ]);
        
        return DB::transaction(function () use ($request) {
            $total = 0;
            $itensValidados = [];

            foreach ($request->itens as $item) {
                // Evita condições de corrida no estoque
                $estoque = Estoque::where('id_sabor', $item['id_sabor'])->lockForUpdate()->first();

                if (!$estoque || $estoque->quantidade < $item['quantidade']) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Sabor não encontrado'
                    ], 422);
                }

                $subtotal = $item['quantidade'] * $estoque->preco;
                $total += $subtotal;

                $itensValidados[] = [
                    'id_sabor' => $item['id_sabor'],
                    'quantidade' => $item['quantidade'],
                    'preco_unitario' => $estoque->preco,
                ];
            }

            $pedido = Pedido::create([
                'id_user' => auth()->id(),
                'status' => 'pendente',
                'total' => $total,
            ]);

            foreach ($itensValidados as $item) {
                $pedido->itens()->create($item);
            }

            // payloads para qr code com gateway ou link indentificador
            $payload = route ('pedidos.confirmar', $pedido->id);
            $pedido->update(['qr_code_payload' => $payload]);

            $qrCodeBase64 = base64_encode(QrCode::format('png')->size(300)->generate($payload));

            return response()->json([
                'status' => true,
                'id_pedido' => $pedido->id,
                'total' => $pedido->total,
                'qr_code' => 'data:image/png;base64,' . $qrCodeBase64,
            ], 201);
        });
    }

    // Faz o polling do status
    public function show(Pedido $pedido): JsonResponse
    {
        return response()->json([
            'status' => true,
            'pedido' => $pedido->load('itens'),
        ]);
    }

    // Uso de um webhook do gateway
    // Num projeto de estudo, pode ser chamado manualmente ou simulado depois de X segundos.
    public function confirmarPagamento(Pedido $pedido): JsonResponse
    {
        if ($pedido->status === 'pago') {
            return response()->json([
                'status' => true,
                'mensage' => 'Já foi pago',
            ]);
        }

        return DB::transaction(function () use ($pedido) {
            foreach ($pedido->itens as $item) {
                $estoque = Estoque::where('id_sabor', $item->id_sabor)->lockForUpdate()->first();
                
                if (!$estoque || $estoque->quantidade < $item->quantidade) {
                    // pagamento chegou mas estoque sumiu nesse meio tempo -> trate como estorno/alerta
                    return response()->json([
                        'status' => false,
                        'message' => 'Estoque insuficiente no momento da confirmação'
                    ], 409);
                }

                $estoque->decrement('quantidade', $item->quantidade);
            }

            $pedido->update(['status' => 'pago']);

            return response()->json([
                'status' => true,
                'message' => 'Pagamento confirmado, estoque atualizado'
            ]);
        });
    }
}

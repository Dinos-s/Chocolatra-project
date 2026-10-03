<?php

namespace App\Http\Controllers;

use App\Models\Estoque;
use App\Models\Pedido;
use App\Models\Sabor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\Exceptions\MPApiException;
use MercadoPago\MercadoPagoConfig;
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
                $sabor = Sabor::find($item['id_sabor']);

                if (!$estoque || $estoque->quantidade < $item['quantidade']) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Sabor não encontrado'
                    ], 422);
                }

                $subtotal = $item['quantidade'] * $sabor->preco;
                $total += $subtotal;

                $itensValidados[] = [
                    'id_sabor' => $item['id_sabor'],
                    'quantidade' => $item['quantidade'],
                    'preco_unitario' => $sabor->preco,
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

            // // payloads para qr code com gateway ou link indentificador
            // $payload = route('pedidos.confirmar', $pedido->id);
            // $pedido->update(['qr_code_payload' => $payload]);

            // // gera em SVG em vez de PNG -> não depende da extensão Imagick
            // $qrCodeSvg = QrCode::format('svg')->size(300)->generate($payload);
            // $qrCodeBase64 = base64_encode($qrCodeSvg);

            MercadoPagoConfig::setAccessToken(env('MERCADOPAGO_ACCESS_TOKEN'));

            $client = new PaymentClient();

            try {
                $payment = $client->create([
                    "transaction_amount" => $total,
                    "description" => "Pedido #{$pedido->id} - Chocolatra",
                    "payment_method_id" => "pix",
                    "external_reference" => $pedido->id,
                    "payer" => [
                        "email" => auth()->user()->email,
                    ],
                    "external_reference" => $pedido->id,
                ]);

                $pedido->update([
                    'mp_payment_id' => $payment->id, 'mp_status' => $payment->status,
                    'qr_code_payload' => $payment->point_of_interaction->transaction_data->qr_code ?? null,
                ]);

                $qrCodeBase64 = $payment->point_of_interaction->transaction_data->qr_code_base64 ?? null;

                return response()->json([
                    'status' => true,
                    'id_pedido' => $pedido->id,
                    'total' => $pedido->total,
                    'qr_code' => 'data:image/png;base64,' . $qrCodeBase64,   // <-- mime type mudou
                    'pix_payload' => $paymente->point_of_interaction->transaction_data->qr_code ?? null
                ], 201);

            } catch (MPApiException $e) {
                return response()->json([
                    'status' => false,
                    'message' => 'Erro ao criar pagamento PIX',
                    'error'=>$e->getMessage(),
                ], 500);
            }
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

    public function meusPedidos(Request $request): JsonResponse {
        $query = Pedido::with('itens.sabor')->where('id_user', $request->user()->id)->orderBy('created_at', 'desc');

        if ($request->filled('data_inicio')) {
            $query->whereDate('created_at', '>=', $request->data_inicio);
        }

        if ($request->filled('data_fim')) {
            $query->whereDate('created_at', '<=', $request->data_fim);
        }

        $pedidos = $query->paginate(10);

        return response()->json([
            'status' => true,
            'pedidos' => $pedidos
        ]);
    }

    // webhook do mercado pago
    public function webhook(Request $request): JsonResponse
    {
        $type = $request->input('type');
        $dataId = $request->input('data.id');

        if ($type === 'payment' && $dataId) {
            MercadoPagoConfig::setAccessToken(env('MERCADOPAGO_ACCESS_TOKEN'));
            $client = new PaymentClient();

            try {
                $payment = $client->get($dataId);
                
                $pedido = Pedido::where('mp_payment_id', $payment->id)->first();

                if ($pedido && $payment->status === 'approved' && $pedido->status !== 'pago') {
                    // confirma o pagamento
                    $this->confirmarPagamento($pedido);
                }

                $pedido?->update(['mp_status' => $payment->status]);

            } catch (\Exception $e) {
                
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Webhook recebido com sucesso'
        ]);
    }
}

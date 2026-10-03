<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $table = 'pedidos';

    protected $fillable = [
        'id_user',
        'status',
        'total',
        'qr_code_payload',
        'mp_payment_id',
        'mp_status',
    ];

    public function itens()
    {
        return $this->hasMany(PedidoItem::class, 'id_pedido');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PedidoItem extends Model
{
    protected $table = 'pedido_itens';

    protected $fillable = [
        'id_pedido',
        'id_sabor',
        'quantidade',
        'preco_unitario',
    ];

    public function sabor(): BelongsTo {
        return $this->belongsTo(Sabor::class, 'id_sabor');
    }
}

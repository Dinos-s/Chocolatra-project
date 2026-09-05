<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Estoque extends Model
{
    protected $table = 'estoque_trufas';

    protected $fillable = [
        'id',
        'id_sabor',
        'quantidade',
        'preco',
    ];

    public function sabor(): BelongsTo
    {
        return $this->belongsTo(Sabor::class, 'id_sabor');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estoque extends Model
{
    protected $table = 'estoque_trufas';

    protected $fillable = [
        'id',
        'id_trufa',
        'quantidade',
        'preco',
    ];
}

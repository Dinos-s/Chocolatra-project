<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trufa extends Model
{
    protected $table = 'trufas';

    protected $fillable = [
        'id',
        'sabor',
        'quantidade',
        'preco',
    ];
}

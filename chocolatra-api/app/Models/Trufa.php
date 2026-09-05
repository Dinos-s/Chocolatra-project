<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Trufa extends Model
{
    protected $table = 'trufas';

    protected $fillable = [
        'id',
        'id_sabor',
        'quantidade',
    ];

    public function sabor(): BelongsTo
    {
        return $this->belongsTo(Sabor::class, 'id_sabor');
    }
}

<?php

namespace App\Models;

use App\Models\Concerns\TemImagemDeSabor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Trufa extends Model
{
    use TemImagemDeSabor;
    protected $table = 'trufas';
    protected $appends = ['img_url'];

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

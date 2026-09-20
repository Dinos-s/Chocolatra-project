<?php

namespace App\Models;

use App\Models\Concerns\TemImagemDeSabor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Sabor extends Model
{
    use TemImagemDeSabor;

    protected $table = 'sabor_trufas';
    protected $appends = ['img_url'];

    protected $fillable = [
        'id',
        'sabor',
        'image',
        'preco',
    ];

    public function trufas(): HasMany
    {
        return $this->hasMany(Trufa::class, 'id_sabor');
    }

    public function estoque(): HasOne
    {
        return $this->hasOne(Estoque::class, 'id_sabor');
    }

    // public function getImgUrlAttribute(): string
    // {
    //     return asset('images/sabores/' . $this->image);
    // }
}

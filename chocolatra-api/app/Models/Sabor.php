<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Sabor extends Model
{
    protected $table = 'sabor_trufas';

    protected $fillable = [
        'id',
        'sabor',
    ];

    public function trufas(): HasMany
    {
        return $this->hasMany(Trufa::class, 'id_sabor');
    }

    public function estoque(): HasOne
    {
        return $this->hasOne(Estoque::class, 'id_sabor');
    }
}

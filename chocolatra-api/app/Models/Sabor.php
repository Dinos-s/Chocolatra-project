<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sabor extends Model
{
    protected $table = 'sabor';

    protected $fillable = [
        'id',
        'sabor',
    ];
}

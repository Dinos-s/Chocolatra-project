<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; // Mudar aqui!
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Cliente extends Authenticatable
{
    protected $table = 'clientes';

    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'cpf',
        'cep',
        'logradouro',
        'bairro',
        'cidade',
        'uf',
        'complemento'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}

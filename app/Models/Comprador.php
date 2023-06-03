<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comprador extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'cpf',
        'birth_date',
        'state',
        'city',
        'credits'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function produtos()
    {
        return $this->belongsToMany(Produto::class)
            ->withPivot('cost')->withTimestamps();
    }

    public function produtos_favorito()
    {
        return $this->belongsToMany(Produto::class, 'favoritos')
            ->withTimestamps();
    }

    public function carrinhos()
    {
        return $this->belongsToMany(Produto::class, 'carrinhos')
            ->withTimestamps();
    }

    public function avaliacoes()
    {
        return $this->belongsToMany(Produto::class, 'avaliacao')
            ->withPivot('rating')
            ->withTimestamps();
    }
}

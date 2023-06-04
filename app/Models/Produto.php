<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendedor_id',
        'name',
        'description',
        'price',
        'category',
        'visualization'
    ];

    public function vendedor()
    {
        return $this->belongsTo(Vendedor::class);
    }
    
    public function compradors()
    {
        return $this->belongsToMany(Comprador::class, 'vendas')
            ->withPivot('cost', 'rating', 'id')->withTimestamps();
    }

    public function imagems()
    {
        return $this->hasMany(Imagem::class);
    }

    public function compradors_favorito()
    {
        return $this->belongsToMany(Comprador::class, 'favoritos')
            ->withTimestamps();
    }

    public function carrinhos()
    {
        return $this->belongsToMany(Comprador::class, 'carrinhos')
            ->withTimestamps();
    }

    public function comentarios()
    {
        return $this->belongsToMany(Comprador::class, 'comentarios')
            ->withPivot('comentary')
            ->withTimestamps();
    }
}

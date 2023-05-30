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
        'category'
    ];

    public function vendedor()
    {
        return $this->belongsTo(Vendedor::class);
    }
    
    public function compradors()
    {
        return $this->belongsToMany(Comprador::class);
    }
}
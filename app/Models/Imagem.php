<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Imagem extends Model
{
    use HasFactory;

    protected $fillable = [
        'produto_id',
        'path'
    ];

    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }
}

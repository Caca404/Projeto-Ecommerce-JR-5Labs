<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
class Vendedor extends User
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'credits'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function produtos()
    {
        return $this->hasMany(Produto::class);
    }
}

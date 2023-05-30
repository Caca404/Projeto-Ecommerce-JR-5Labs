<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    public function show(Request $request)
    {
        $produto = Produto::find($request->id);

        return view('comprador/produto', ['produto' => $produto]);
    }
}

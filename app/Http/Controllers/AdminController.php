<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function compradores(Request $request)
    {
        $compradores = User::where('type', 'comprador')->get();

        return view('/admin/compradores', [
            "compradores" => $compradores
        ]);
    }

    public function vendedores(Request $request)
    {
        $vendedores = User::where('type', 'vendedor')->get();

        return view('/admin/vendedores', [
            "vendedores" => $vendedores
        ]);
    }

    public function produtos(Request $request)
    {
        $produtos = Produto::all('name','price', 'category');

        return view('/admin/produtos', [
            "produtos" => $produtos
        ]);
    }

    public function decisionStatusVendedor(Request $request)
    {
        $vendedor = User::find($request->id)->vendedor;
        $vendedor->status = $request->decision;
        if($vendedor->save())
            return back()->with(['message', 'Alegria']);
        return back()->withErrors(['message' => 'Não foi salvo.']);
    }
}

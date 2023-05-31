<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use App\Models\Vendedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class VendedorController extends Controller
{
    public function dashboard(Request $request)
    {
        $produtos = Auth::user()->vendedor->produtos;
        
        return view('vendedor/dashboard', ['produtos' => $produtos]);
    }

    public function minhasVendas(Request $request)
    {
        $vendas = Produto::where('vendedor_id', Auth::user()->vendedor->id)
            ->with('compradors')->with('imagems')->get();
        
        return view('vendedor/minhasVendas', ['vendas' => $vendas]);
    }
}

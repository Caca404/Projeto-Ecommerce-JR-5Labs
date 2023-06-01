<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use App\Models\Vendedor;
use App\Utils\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;

class VendedorController extends Controller
{
    public function dashboard(Request $request)
    {
        if(gettype($request->category) == "string")
            $request->category = json_decode($request->category);

        $whereArray = [];

        if(!empty($request->smallerPrice))
            $whereArray[] = ['price', '>=', $request->smallerPrice];

        if(!empty($request->biggerPrice))
            $whereArray[] = ['price', '<=', $request->biggerPrice];

        if(!empty($request->name))
            $whereArray[] = ['name', 'LIKE', '%'.$request->name."%"];

        if(!empty($whereArray)){
            $produtos = Auth::user()->vendedor->produtos()
                ->where($whereArray)
                ->whereIn('category', !empty($request->category) ?
                    (is_array($request->category) ? [...$request->category] : [$request->category]) : 
                    [...Utils::categorias])
                ->get();
        }
        else{
            $produtos = Auth::user()->vendedor->produtos()
                ->whereIn('category', !empty($request->category) ?
                    (is_array($request->category) ? [...$request->category] : [$request->category]) : 
                    [...Utils::categorias])
                ->get();
        }


        return view('vendedor/dashboard', [
            'produtos' => $produtos,
            'categorias' => Utils::categorias,
            "isRequestEmpty" => empty($whereProduto) && empty($whereProdutoPivot) 
                && empty($request->category)
        ]);
    }

    public function minhasVendas(Request $request)
    {
        $vendas = Produto::where('vendedor_id', Auth::user()->vendedor->id)
            ->with('compradors')->with('imagems')->get();
        
        return view('vendedor/minhasVendas', ['vendas' => $vendas]);
    }
}

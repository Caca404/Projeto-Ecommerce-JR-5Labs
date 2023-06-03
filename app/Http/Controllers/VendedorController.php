<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use App\Models\Vendedor;
use App\Utils\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class VendedorController extends Controller
{
    public function dashboard(Request $request)
    {
        if(!empty($request->category)){
            if(gettype($request->category[0]) == "string")
                $request->category = json_decode($request->category[0]);
        }

        $request->validate([
            'name' => 'sometimes|required|string',
            'smallerPrice' => 'sometimes|required|numeric|min:0|lte:biggerPrice',
            'biggerPrice' => 'sometimes|required|numeric|min:0|gte:smallerPrice',
            'category' => 'sometimes|required|array'
        ]);

        if(!empty($request->category)){
            $validator = Validator::make($request->category, [
                "category.*" => Rule::in(Utils::categorias)
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator);
            }
        }


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
        if(Auth::user()->vendedor->status == "P")
            return redirect()->route('vendedor/dashboard');

        $vendas = Produto::where('vendedor_id', Auth::user()->vendedor->id)
            ->with('compradors')->with('imagems')->get();
        
        return view('vendedor/minhasVendas', ['vendas' => $vendas]);
    }
}

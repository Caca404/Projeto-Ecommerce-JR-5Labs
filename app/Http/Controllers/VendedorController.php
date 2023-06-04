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

        $orderTypes = [
            'name-asc' => "A-Z", 
            'name-desc' => "Z-A",
            'price-asc' => "Mais Barato", 
            'price-desc' => "Mais Caro"
        ]; 

        $request->validate([
            'name' => 'sometimes|required|string',
            'smallerPrice' => 'sometimes|required|numeric|min:0|lte:biggerPrice',
            'biggerPrice' => 'sometimes|required|numeric|min:0|gte:smallerPrice',
            'category' => 'sometimes|required|array',
            'order' => Rule::in(array_keys($orderTypes))
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

        $categories = null;
        if(!empty($request->category)){
            if(is_array($request->category)) $categories = [...$request->category];
            else $categories = [$request->category];
        }
        else $categories = Utils::categorias;

        $order = ['name', 'asc'];
        if(!empty($request->order)) $order = explode('-', $request->order);

        $produtos = Auth::user()->vendedor->produtos()
                ->whereIn('category', $categories);

        if(!empty($whereArray))
            $produtos = $produtos->where($whereArray);
        
        $produtos = $produtos->orderBy($order[0], $order[1])->get();



        return view('vendedor/dashboard', [
            'produtos' => $produtos,
            'categorias' => Utils::categorias,
            "isRequestEmpty" => empty($whereProduto) && empty($whereProdutoPivot) 
                && empty($request->category),
            'orderTypes' => $orderTypes
        ]);
    }

    public function minhasVendas(Request $request)
    {
        if(Auth::user()->vendedor->status == "P")
            return redirect()->route('vendedor/dashboard');

        $vendas = Produto::where('vendedor_id', Auth::user()->vendedor->id)
            ->with('compradors')->with('imagems')->get();

        // $numeroAvaliacoes = 0;
        // $mediaAvaliacoes = 0;

        // $avaliacoes = $produto->compradors()
        //     ->wherePivot('rating', '<>', 'null')
        //     ->where('produto_id', $produto->id)
        //     ->get();

        // foreach ($avaliacoes as $avaliacao) {
        //     $mediaAvaliacoes += $avaliacao->pivot->rating;
        //     $numeroAvaliacoes++;
        // }

        // if($numeroAvaliacoes > 0)
        //     $mediaAvaliacoes = round($mediaAvaliacoes/$numeroAvaliacoes);
        
        return view('vendedor/minhasVendas', ['vendas' => $vendas]);
    }
}

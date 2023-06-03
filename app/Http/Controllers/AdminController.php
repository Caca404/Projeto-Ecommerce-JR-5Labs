<?php

namespace App\Http\Controllers;

use App\Models\Comprador;
use App\Models\Produto;
use App\Models\User;
use App\Models\Vendedor;
use App\Utils\Utils;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function compradores(Request $request)
    {
        if(gettype($request->states) == "string")
            $request->states = json_decode($request->states);

        $request->validate([
            'name' => 'string|nullable',
            'cpf' => 'string|max:11',
            'minDate' => 'date|lte:maxDate',
            'maxDate' => 'date|gte:minDate',
            'minCredit' => 'numeric|min:0|lte:maxCredit',
            'maxCredit' => 'numeric|min:0|gte:minCredit',
            'states' => Rule::in(array_keys(Utils::states)),
            'order' => Rule::in([
                'name-asc',
                'name-desc',
                'credits-asc',
                'credits-desc',
                'birth_date-desc',
                'birth_date-asc',
            ])
        ]);

        $whereArray = [];
        $whereParent = [];
        if(!empty($request->name)) $whereParent[] = ['name', 'like', '%'.$request->name.'%'];
        if(!empty($request->cpf)) $whereArray[] = ['cpf', 'like', '%'.$request->cpf.'%'];
        if(!empty($request->minDate)) $whereArray[] = ['birth_date', '>=', $request->minDate];
        if(!empty($request->maxDate)) $whereArray[] = ['birth_date', '<=', $request->maxDate];
        if(!empty($request->minCredit)) $whereArray[] = ['credits', '>=', $request->minCredit];
        if(!empty($request->maxCredit)) $whereArray[] = ['credits', '<=', $request->maxCredit];

        $states = null;
        if(!empty($request->states)){
            if(is_array($request->states)) $states = [...$request->states];
            else $states = [$request->states];
        }
        else $states = array_keys(Utils::states);

        $order = ['name', 'asc'];
        if(!empty($request->order)) $order = explode('-', $request->order);

        

        $compradores = Comprador::whereIn('state', $states);

        if(count($whereArray))
            $compradores = $compradores->where($whereArray);
        
        if(!empty($whereParent)){

            $users = User::where($whereParent)->get();

            $compradores = $compradores->whereBelongsTo($users);
        }

        $compradores = $compradores->get();

        $orderName = $order[0];
        $orderSort = 'sortBy'.($order[1] == 'asc' ? '' : 'Desc');

        $compradores = $compradores->$orderSort(function($comprador, $key) use ($orderName) {
            if($orderName == 'name') return strtolower($comprador->user->$orderName);
            else return $comprador->$orderName;
        })->values();



        return view('/admin/compradores', [
            "compradores" => $compradores,
            "states" => Utils::states,
            'isRequestEmpty' => empty($whereArray) && empty($whereParent) 
                && empty($request->states)
        ]);
    }

    public function vendedores(Request $request)
    {
        $request->validate([
            'name' => 'string|nullable',
            'minCredit' => 'numeric|min:0|lte:maxCredit',
            'maxCredit' => 'numeric|min:0|gte:minCredit',
            'status' => Rule::in(['A', 'P', 'R'])
        ]);

        $whereArray = [];
        $whereParent = [];
        if(!empty($request->name)) $whereParent[] = ['name', 'like', '%'.$request->name.'%'];
        if(!empty($request->minCredit)) $whereArray[] = ['credits', '>=', $request->minCredit];
        if(!empty($request->maxCredit)) $whereArray[] = ['credits', '<=', $request->maxCredit];
        if(!empty($request->status)) $whereArray[] = ['status', $request->status];

        $order = ['name', 'asc'];
        if(!empty($request->order)) $order = explode('-', $request->order);




        $vendedores = null;

        if(count($whereParent)){
            $users = User::where($whereParent)->get();

            $vendedores = Vendedor::whereBelongsTo($users);
        }

        if(count($whereArray)){
            if($vendedores == null) $vendedores = Vendedor::where($whereArray);
            else $vendedores = $vendedores->where($whereArray);
        }

        if($vendedores != null) $vendedores = $vendedores->get();
        else $vendedores = Vendedor::all();

        $orderName = $order[0];
        $orderSort = 'sortBy'.($order[1] == 'asc' ? '' : 'Desc');

        $vendedores = $vendedores->$orderSort(function($vendedor, $key) use ($orderName) {
            if($orderName == "name") return strtolower($vendedor->user->name);
            return $vendedor->$orderName;
        })->values();



        return view('/admin/vendedores', [
            "vendedores" => $vendedores,
            "states" => Utils::states,
            'isRequestEmpty' => empty($whereArray) && empty($whereParent) 
                && empty($request->states)
        ]);
    }

    public function produtos(Request $request)
    {
        if(gettype($request->categories) == "string")
            $request->categories = json_decode($request->categories);

        $request->validate([
            'name' => 'string|nullable',
            'minPrice' => 'numeric|min:0|lte:maxPrice',
            'maxPrice' => 'numeric|min:0|gte:minPrice',
            'category' => Rule::in(Utils::categorias)
        ]);

        $whereArray = [];
        if(!empty($request->name)) $whereArray[] = ['name', 'like', '%'.$request->name.'%'];
        if(!empty($request->minPrice)) $whereArray[] = ['price', '>=', $request->minPrice];
        if(!empty($request->maxPrice)) $whereArray[] = ['price', '<=', $request->maxPrice];

        $categories = null;
        if(!empty($request->categories)){
            if(is_array($request->categories)) $categories = [...$request->categories];
            else $categories = [$request->categories];
        }
        else $categories = Utils::categorias;

        $order = ['name', 'asc'];
        if(!empty($request->order)) $order = explode('-', $request->order);



        $produtos = Produto::whereIn('category', $categories);

        if(count($whereArray))
            $produtos = $produtos->where($whereArray);
        
        $produtos = $produtos->orderBy($order[0], $order[1])
            ->get();



        return view('/admin/produtos', [
            "produtos" => $produtos,
            'categorias' => Utils::categorias,
            'isRequestEmpty' => count($whereArray) && empty($request->categories)
        ]);
    }

    public function decisionStatusVendedor(Request $request)
    {
        $request->merge([
            'id' => $request->route('id'),
            'decision' => $request->route('decision'),
        ]);
        $request->validate([
            'id' => 'required|exists:vendedors,id',
            'decision' => [
                'required',
                Rule::in([
                    'S', 'N'
                ])
            ]
        ]);

        $vendedor = Vendedor::find($request->id);
        
        if(!empty($vendedor)){
            $vendedor->status = $request->decision;
            if($vendedor->save())
                return back()->with([
                    'message' => 'Vendedor foi '.($request->decision == "A" ? 'Aceito' : "Rejeitado")
                        .'com sucesso.'
                ]);
        }

        return back()->withErrors(['message' => 'A decisão não foi salva.']);
    }
}

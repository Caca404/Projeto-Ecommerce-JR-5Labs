<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateCompradorRequest;
use App\Models\Compra;
use App\Models\Produto;
use App\Models\User;
use App\Models\Venda;
use App\Models\Vendedor;
use App\Utils\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class CompradorController extends Controller
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

        $produtos = Produto::where($whereArray)
        ->whereIn('category', !empty($request->category) ?
            (is_array($request->category) ? [...$request->category] : [$request->category]) : 
            [...Utils::categorias])
        ->with('imagems')->get();

        return view('comprador/dashboard', [
            "produtos" => $produtos,
            'categorias' => Utils::categorias,
            'isRequestEmpty' => empty($whereArray) && empty($request->category)
        ]);
    }

    public function perfil(Request $request){
        $user = Auth::user();

        return view('comprador/perfil', [
            "name" => $user->name,
            "email" => $user->email,
            "cpf" => $user->comprador->cpf,
            "birth_date" => $user->comprador->birth_date,
            "state" => Utils::states[$user->comprador->state],
            "stateUF" => $user->comprador->state,
            "city" => $user->comprador->city,
            "states" => Utils::states
        ]);
    }

    public function buy(Request $request)
    {
        $produto = Produto::find($request->id);
        $comprador = Auth::user()->comprador;
        
        if($comprador->credits < $produto->price)
            return back()->withErrors([
                'credits' => "Infelizmente a compra não foi feita.
                    Você não tem créditos o suficiente!"
            ]);
        
        $vendedor = $produto->vendedor;

        $comprador->produtos()->attach($produto, ['cost' => $produto->price]);

        $comprador->credits -= $produto->price;
        $vendedor->credits += $produto->price;

        $comprador->save();
        $vendedor->save();

        return redirect()->route('comprador/minhas-compras');
    }

    public function myOrders(Request $request)
    {
        if(gettype($request->category) == "string")
            $request->category = json_decode($request->category);

            
        $whereProduto = [];
        if(!empty($request->name))
            $whereProduto[] = ['name', 'LIKE', '%'.$request->name."%"];


        $whereProdutoPivot = [];
        if(!empty($request->smallerPrice))
            $whereProdutoPivot[] = ['>=', $request->smallerPrice];

        if(!empty($request->biggerPrice))
            $whereProdutoPivot[] = ['<=', $request->biggerPrice];


        $categories = null;
        if(!empty($request->category)){
            if(is_array($request->category)) $categories = [...$request->category];
            else $categories = [$request->category];
        }
        else $categories = [...Utils::categorias];


        if(empty($whereProduto) && empty($whereProdutoPivot)) {
            $compras = Auth::user()->comprador->produtos()
                ->whereIn('category', $categories);  
        }

        if(!empty($whereProduto) && !empty($whereProdutoPivot)){
            if(count($whereProdutoPivot) > 1){
                
                $compras = Auth::user()->comprador->produtos()
                    ->where($whereProduto)
                    ->wherePivotBetween('cost', [
                        $whereProdutoPivot[0][1],
                        $whereProdutoPivot[1][1]
                    ])
                    ->whereIn('category', $categories)
                    ->get();
            }
            else{
                $compras = Auth::user()->comprador->produtos()
                    ->where($whereProduto)
                    ->wherePivot('cost', $whereProdutoPivot[0][0], $whereProdutoPivot[0][1])
                    ->whereIn('category', $categories)
                    ->get();
            }
        }
        else{
            if(!empty($whereProduto)){
                $compras = Auth::user()->comprador->produtos()
                    ->where($whereProduto)
                    ->whereIn('category', $categories)
                    ->get();
            }

            if(!empty($whereProdutoPivot)){
                if(count($whereProdutoPivot) > 1){
                    $compras = Auth::user()->comprador->produtos()
                        ->wherePivotBetween('cost', [
                            $whereProdutoPivot[0][1],
                            $whereProdutoPivot[1][1]
                        ])
                        ->whereIn('category', $categories)
                        ->get();
                }
                else{
                    $compras = Auth::user()->comprador->produtos()
                        ->wherePivot('cost', $whereProdutoPivot[0][0], $whereProdutoPivot[0][1])
                        ->whereIn('category', $categories)
                        ->get();
                }
            }
        }

        return view('comprador/minhasCompras', [
            "compras" => $compras,
            "categorias" => Utils::categorias,
            "isRequestEmpty" => empty($whereProduto) && empty($whereProdutoPivot) 
                && empty($request->category)
        ]);
    }

    public function update(UpdateCompradorRequest $request, User $user)
    {
        $user = Auth::user();

        $user->update($request->all());

        return redirect()->route('comprador/perfil');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'old_password' => ['required'],
            'password' => [
                'required', 
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ],
            'password_confirmation' => ['required', 'same:password'],
        ]);

        if(Hash::check($request->old_password, Auth::user()->password)){
            Auth::user()->fill([
                "password" => Hash::make($request->password)
            ])->save();

            return redirect()->route('comprador/perfil');
        }

        return back()->withErrors(['old_password' => "Senha incorreta."]);
    }
}

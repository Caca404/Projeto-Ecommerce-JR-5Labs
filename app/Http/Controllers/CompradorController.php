<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateCompradorRequest;
use App\Models\Compra;
use App\Models\Produto;
use App\Models\User;
use App\Models\Venda;
use App\Models\Vendedor;
use App\Utils\Utils;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class CompradorController extends Controller
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



        $produtos = Produto::whereIn('category', $categories);

        if(count($whereArray))
            $produtos = $produtos->where($whereArray);

        $produtos = $produtos->orderBy($order[0], $order[1])
            ->with('imagems')->get();



        return view('comprador/dashboard', [
            "produtos" => $produtos,
            'categorias' => Utils::categorias,
            'isRequestEmpty' => empty($whereArray) && empty($request->category),
            'orderTypes' => $orderTypes
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
        
        if($produto == null)
            return back()->withErrors([
                'id' => "Produto inexistente"
            ]);

        $comprador = Auth::user()->comprador;
        
        if($comprador->credits < $produto->price)
            return back()->withErrors([
                'credits' => "Infelizmente a compra não foi feita.
                    Você não tem créditos o suficiente!"
            ]);
        
        $vendedor = $produto->vendedor;

        $comprador->produtos()->attach($produto, ['cost' => $produto->price]);

        if($comprador->carrinhos()->where('produto_id', $produto->id)->count() > 0)
            $comprador->carrinhos()->detach($produto->id);

        $comprador->credits -= $produto->price;
        $vendedor->credits += $produto->price;

        $comprador->save();
        $vendedor->save();

        return redirect()->route('comprador/minhas-compras');
    }

    public function myOrders(Request $request)
    {
        if(!empty($request->category)){
            if(gettype($request->category[0]) == "string")
                $request->category = json_decode($request->category[0]);
        }

        $orderTypes = [
            'name-asc' => "A-Z", 
            'name-desc' => "Z-A",
            'cost-asc' => "Mais Barato", 
            'cost-desc' => "Mais Caro",
            'created_at-desc' => "Mais Recente",
            'created_at-asc' => "Mais Velho"
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
        else $categories = Utils::categorias;

        $order = ['name', 'asc'];
        if(!empty($request->order)) $order = explode('-', $request->order);




        $compras = Auth::user()->comprador->produtos()
            ->whereIn('category', $categories);

        if(!empty($whereProduto))
            $compras = $compras->where($whereProduto);

        if(!empty($whereProdutoPivot)){
            if(count($whereProdutoPivot) > 1){
                $compras = $compras->wherePivotBetween('cost', [
                    $whereProdutoPivot[0][1],
                    $whereProdutoPivot[1][1]
                ]);
            }
            else
                $compras = $compras->wherePivot('cost', $whereProdutoPivot[0][0], $whereProdutoPivot[0][1]);
        }

        $compras = $compras->get();

        $orderName = $order[0];
        $orderSort = 'sortBy'.($order[1] == 'asc' ? '' : 'Desc');

        $compras = $compras->$orderSort(function($produto, $key) use ($orderName) {
            if(in_array($orderName,["cost", 'created_at'])) return $produto->pivot->$orderName;
            return strtolower($produto->$orderName);
        })->values();



        return view('comprador/minhasCompras', [
            "compras" => $compras,
            "comprador" => Auth::user()->comprador,
            "categorias" => Utils::categorias,
            "isRequestEmpty" => empty($whereProduto) && empty($whereProdutoPivot) 
                && empty($request->category),
            'orderTypes' => $orderTypes
        ]);
    }

    public function update(UpdateCompradorRequest $request, User $user)
    {
        $user = Auth::user();
        
        if(!empty($request->email)){
            if($request->email != $user->email){
                $user->email_verified_at = null;
                $user->save();
            }
        }

        $user->update($request->only(['name', 'email']));

        $user->comprador->cpf = $request->cpf;
        $user->comprador->birth_date = $request->birth_date;
        $user->comprador->state = $request->state;
        $user->comprador->city = $request->city;
        $user->comprador->save();


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

    public function favoritar(Request $request)
    {
        $request->merge(['id' => $request->route('id')]);
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:produtos,id'
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $produto = Produto::find($request->id);
        Auth::user()->comprador->produtos_favorito()->save($produto);
        
        return back()->with([
            'status' => 'Produto está na sua lista de favoritos.'
        ]);
    }

    public function desfavoritar(Request $request)
    {
        $request->merge(['id' => $request->route('id')]);
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:produtos,id'
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $comprador = Auth::user()->comprador;

        $comprador->produtos_favorito()->detach($request->id);
        
        return back()->with([
            'status' => "Produto foi removido da sua lista de favoritos com sucesso."
        ]);
    }

    public function myFavorites(Request $request)
    {
        $produtos = Auth::user()->comprador->produtos_favorito()->get();

        return view('comprador/meusFavoritos', [
            "produtos" => $produtos,
            'comprador' => Auth::user()->comprador
        ]);
    }

    public function myShoppingCart(Request $request)
    {
        $carrinho = Auth::user()->comprador->carrinhos()->get();

        return view('comprador/carrinho', [
            'carrinho' => $carrinho,
            'comprador' => Auth::user()->comprador
        ]);
    }

    public function addToShoppingCart(Request $request)
    {
        $request->merge(['id' => $request->route('id')]);
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:produtos,id'
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $produto = Produto::find($request->id);

        $comprador = Auth::user()->comprador;
        $comprador->carrinhos()->save($produto);
        
        return back()->with('status', 'Produto adicionado com sucesso.');
    }

    public function removeFromShoppingCart(Request $request)
    {
        $request->merge(['id' => $request->route('id')]);
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:produtos,id'
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $comprador = Auth::user()->comprador;
        $comprador->carrinhos()->detach($request->id);
        
        return back()->with('status', 'Produto removido com sucesso.');
    }

    public function buyFromShoppingCart(Request $request)
    {
        $comprador = Auth::user()->comprador;
        $carrinho = $comprador->carrinhos()->get();

        $total = 0;
        foreach ($carrinho as $produto) {
            $total += $produto->price;
        }

        if($comprador->credits < $total)
            return back()->withErrors([
                'credits' => "Infelizmente a compra não foi feita.
                    Você não tem créditos o suficiente!"
            ]);

        foreach ($carrinho as $produto) {
            $vendedor = $produto->vendedor;
    
            $comprador->produtos()->attach($produto, ['cost' => $produto->price]);
            $comprador->carrinhos()->detach($produto->id);
    
            $comprador->credits -= $produto->price;
            $vendedor->credits += $produto->price;
    
            $comprador->save();
            $vendedor->save();
        }

        return redirect()->route('comprador/minhas-compras');
    }

    public function clearShoppingCart(Request $request)
    {
        $comprador = Auth::user()->comprador;
        $carrinho = $comprador->carrinhos()->get();

        foreach ($carrinho as $produto) {
            $comprador->carrinhos()->detach($produto->id);
        }

        return back()->with('status', 'Carrinho de compras limpado com sucesso.');
    }

    public function rateCompra(Request $request)
    {
        $request->merge([
            'id' => $request->route('id'),
            'rating' => $request->route('rating'),
        ]);
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:produtos,id',
            'rating' => [
                'required',
                Rule::in([
                    1,2,3,4,5
                ])
            ]
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $comprador = Auth::user()->comprador;
        $hasVenda = $comprador->produtos()
            ->wherePivot('id', $request->id)
            ->count() > 0;

        if($hasVenda){
            DB::table('vendas')
                ->where('id', $request->id)
                ->update(['rating' => $request->rating]);
        }
        else return back()->withErrors(['id', 'Compra inexistente.']);

        return back()->with('status', 'Avaliação feita com sucesso.');
    }

    public function createComentary(Request $request)
    {
        $request->validate([
            'comentary' => 'required|string|max:3000'
        ]);

        $produto = Produto::find($request->id);
        if($produto == null)
            return back()->withErrors(['id', 'Produto inexistente.']);

        $produto->comentarios()->attach(Auth::user()->id, [
            'comentary' => $request->comentary
        ]);

        return back()->with(['status', 'Comentário adicionado ao produto com sucesso.']);
    }
}
<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateCompradorRequest;
use App\Models\Compra;
use App\Models\Produto;
use App\Models\User;
use App\Models\Vendedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class CompradorController extends Controller
{
    public const states = [
        "AC" => "Acre", 
        "AL" => "Alagoas", 
        "AP" => "Amapá", 
        "AM" => "Amazonas", 
        "BA" => "Bahia",
        "CE" => "Ceará", 
        "DF" => "Distrito Federal", 
        "ES" => "Espirito Santo", 
        "GO" => "Goiás", 
        "MA" => "Maranhão",
        "MT" => "Mato Grosso", 
        "MS" => "Mato Grosso do Sul", 
        "MG" => "Minas Gerais", 
        "PA" => "Pará", 
        "PB" => "Paraíba",
        "PR" => "Paraná", 
        "PE" => "Pernambuco", 
        "PI" => "Piauí", 
        "RJ" => "Rio de Janeiro", 
        "RN" => "Rio Grande do Norte",
        "RS" => "Rio Grande do Sul", 
        "RO" => "Rondônia", 
        "RR" => "Roraima", 
        "SC" => "Santa Catarina", 
        "SP" => "São Paulo",
        "SE" => "Sergipe",
        "TO" => "Tocantins"
    ];

    public function dashboard(Request $request)
    {
        $produtos = Produto::all(['id', 'name', 'price']);

        return view('comprador/dashboard', ["produtos" => $produtos]);
    }

    public function perfil(Request $request){
        $user = Auth::user();

        return view('comprador/perfil', [
            "name" => $user->name,
            "email" => $user->email,
            "cpf" => $user->comprador->cpf,
            "birth_date" => $user->comprador->birth_date,
            "state" => self::states[$user->comprador->state],
            "stateUF" => $user->comprador->state,
            "city" => $user->comprador->city,
            "states" => self::states
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

        $comprador->produtos()->save($produto);

        $comprador->credits -= $produto->price;
        $vendedor->credits += $produto->price;

        $comprador->save();
        $vendedor->save();

        return redirect()->route('comprador/minhas-compras');
    }

    public function myOrders(Request $request)
    {
        $compras = Auth::user()->comprador->produtos;
        // $compras = Compra::where('comprador_id', Auth::user()->comprador->id)->get();

        return view('comprador/minhasCompras', ["compras" => $compras]);
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

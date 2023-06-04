<?php

namespace App\Http\Controllers;

use App\Models\Imagem;
use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Utils\Utils;
use Illuminate\Support\Facades\Validator;

class ProdutoController extends Controller
{
    public function produto(Request $request)
    {
        $produto = [];

        if(!empty($request->id)){
            $request->merge(['id' => $request->id]);
            $validator = Validator::make($request->all(), [
                'id' => 'sometimes|required|exists:produtos,id'
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator);
            }
    
            $produto = Produto::where('id', $request->id)
                ->with('imagems')->first();
        }

        return view('vendedor/produto', [
            'produto' => $produto, 
            'categorias' => Utils::categorias
        ]);
    }

    public function show(Request $request)
    {
        $request->merge(['id' => $request->route('id')]);
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:produtos,id'
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $userType = Auth::user()->type == 'comprador';

        $produto = Produto::where('id', $request->id)
            ->with('imagems')
            ->first();

        if($produto == null)
            return back()->withErrors(['id', 'Produto Inexistente']);


        if($userType){
            
            $produto->visualization++;
            $produto->save();
    
    
    
            $isFavorited = $produto->compradors_favorito()
                ->where('comprador_id', Auth::user()->comprador->id)->count();
    
            $isInShoppingCart = $produto->carrinhos()
                ->where('comprador_id', Auth::user()->comprador->id)->count();
        }


        $numeroAvaliacoes = 0;
        $mediaAvaliacoes = 0;

        $avaliacoes = $produto->compradors()
            ->wherePivot('rating', '<>', 'null')
            ->where('produto_id', $produto->id)
            ->get();

        foreach ($avaliacoes as $avaliacao) {
            $mediaAvaliacoes += $avaliacao->pivot->rating;
            $numeroAvaliacoes++;
        }

        if($numeroAvaliacoes > 0)
            $mediaAvaliacoes = round($mediaAvaliacoes/$numeroAvaliacoes);


        $comentarios = $produto->comentarios()
            ->where('produto_id', $produto->id)
            ->get();


        return view('comprador/produto', [
            'produto' => $produto,
            'isFavorited' => $userType ? $isFavorited > 0 : false,
            'isInShoppingCart' => $userType ? $isInShoppingCart > 0 : false,
            'mediaAvaliacoes' => $mediaAvaliacoes,
            'numeroAvaliacoes' => $numeroAvaliacoes,
            'comentarios' => $comentarios
        ]);
    }

    public function createProduto(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
            'category' => [
                'required',
                Rule::in(Utils::categorias)
            ],
            'description' => 'required|max:3000',
            'images' => 'required|array|max:3|min:1',
            'images.*' => [
                'image',
                'mimes:jpg,png',
                'max:5120'
            ]
        ]);

        $produto = new Produto();
        $produto->name = $request->name;
        $produto->price = $request->price;
        $produto->category = $request->category;
        $produto->description = $request->description;
        $produto->visualization = 0;

        Auth::user()->vendedor->produtos()->save($produto);

        foreach ($request->images as $imagem) {
            $imagemModel = new Imagem();

            $extension = $imagem->extension();
            $imageName = md5($imagem->getClientOriginalName().strtotime("now"));
            $imagem->move(public_path('images/products'), $imageName.".".$extension);

            $imagemModel->path = '/images/products/'.$imageName.".".$extension;

            $produto->imagems()->save($imagemModel);
        }
            
        return redirect()->route('vendedor/dashboard');
    }

    public function editProduto(Request $request)
    {
        $produto = Produto::find($request->id);
        if($produto == null) 
            return back()->with([
                'id' => "Produto inexistente."
            ]);

        $request->imagesToRemove = json_decode($request->imagesToRemove[0]);

        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
            'category' => [
                'required',
                Rule::in(Utils::categorias)
            ],
            'description' => 'required|max:3000',
            'images' => 'array|max:3',
            'images.*' => [
                'image',
                'mimes:jpg,png',
                'max:5120'
            ],
            'imagesToRemove' => 'array|max:3',
            'imagesToRemove.*' => 'string'
        ]);

        $produto->name = $request->name;
        $produto->price = $request->price;
        $produto->category = $request->category;
        $produto->description = $request->description;

        $imagesInDatabase = Imagem::where('produto_id', $request->id)->count();

        if(!empty($request->imagesToRemove) && !empty($request->images))
        {
            $imagesTotal = $imagesInDatabase + count($request->images) - count($request->imagesToRemove);
            if($imagesTotal > 3)
                return back()->withErrors(['images', 'The images must not have more than 3 items.']);

            if($imagesTotal == 0)
                return back()->withErrors(['images', 'Precisa de no mínimo uma imagem.']);
        }
        elseif(!empty($request->imagesToRemove))
            if($imagesInDatabase == count($request->imagesToRemove))
                return back()->withErrors(['images', 'Precisa de no mínimo uma imagem.']);

        elseif(!empty($request->images))
            if($imagesInDatabase + count($request->images) > 3)
                return back()->withErrors(['images', 'The images must not have more than 3 items.']);


        if(!empty($request->imagesToRemove)){
            foreach ($request->imagesToRemove as $image) {
                Imagem::where('path', $image)->delete();
                $imagemName = str_replace('/images/products/', '', $image);

                if(in_array($imagemName, scandir(public_path('/images/products/'))))
                    unlink(public_path('images/products').'/'.$imagemName);
            }
        }

        if(!empty($request->images)){
            foreach ($request->images as $imagem) {
                $imagemModel = new Imagem();

                $extension = $imagem->extension();
                $imageName = md5($imagem->getClientOriginalName().strtotime("now"));
                $imagem->move(public_path('images/products'), $imageName.".".$extension);

                $imagemModel->path = '/images/products/'.$imageName.".".$extension;

                $produto->imagems()->save($imagemModel);
            }
        }

        Auth::user()->vendedor->produtos()->save($produto);

        return back()->with(['status' => 'Produto editado com sucesso.']);
    }
}

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
        $request->merge(['id' => $request->route('id')]);
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:produtos,id'
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        if(Auth::user()->vendedor->status == "P") 
            return redirect()->route('vendedor/dashboard');

        $produto = [];

        if(!empty($request->id))
            $produto = Produto::where('id', $request->id)
                ->with('imagems')->first();
        
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


        $produto = Produto::where('id', $request->id)
            ->with('imagems')
            ->first();
        
        $produto->visualization++;
        $produto->save();

        $isFavorited = $produto->compradors_favorito()
            ->where('comprador_id', Auth::user()->comprador->id)->count();

        $isInShoppingCart = $produto->carrinhos()
            ->where('comprador_id', Auth::user()->comprador->id)->count();
        
        $numeroAvaliacoes = 0;
        $mediaAvaliacoes = 0;

        $avaliacoes = $produto->avaliacoes()
            ->where('produto_id', $produto->id)
            ->get();

        foreach ($avaliacoes as $avaliacao) {
            $mediaAvaliacoes += $avaliacao->pivot->rating;
            $numeroAvaliacoes++;
        }

        if($numeroAvaliacoes > 0)
            $mediaAvaliacoes = round($mediaAvaliacoes/$numeroAvaliacoes);

        return view('comprador/produto', [
            'produto' => $produto,
            'isFavorited' => $isFavorited > 0,
            'isInShoppingCart' => $isInShoppingCart > 0,
            'mediaAvaliacoes' => $mediaAvaliacoes,
            'numeroAvaliacoes' => $numeroAvaliacoes
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

            $imagemModel->name = $imageName;
            $imagemModel->mime = $extension;

            $produto->imagems()->save($imagemModel);
        }
            
        return redirect()->route('vendedor/dashboard');
    }

    public function editProduto(Request $request)
    {
        $request->merge(['id' => $request->route('id')]);
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:produtos,id'
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator);
        }
        
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

        $produto = Produto::find($request->id);
        $produto->name = $request->name;
        $produto->price = $request->price;
        $produto->category = $request->category;
        $produto->description = $request->description;

        $imagesInDatabase = Imagem::where('produto_id', $request->id)->count();
        if(!empty($request->imagesToRemove))
            if($imagesInDatabase == count($request->imagesToRemove) && empty($request->images))
                return back()->withErrors(['images', 'Precisa de no mínimo uma imagem.']);

        if(!empty($request->images))
            if($imagesInDatabase + count($request->images) > 3)
                return back()->withErrors(['images', 'The images must not have more than 3 items.']);

        if(!empty($request->imagesToRemove)){
            foreach ($request->imagesToRemove as $image) {
                $imageName = substr(
                    $image, 
                    strrpos($image, '/') + 1, 
                    strrpos($image, '.') - strrpos($image, '/') - 1
                );
                Imagem::where('name', $imageName)->delete();
            }
        }

        if(!empty($request->images)){
            foreach ($request->images as $imagem) {
                $imagemModel = new Imagem();

                $extension = $imagem->extension();
                $imageName = md5($imagem->getClientOriginalName().strtotime("now"));
                $imagem->move(public_path('images/products'), $imageName.".".$extension);

                $imagemModel->name = $imageName;
                $imagemModel->mime = $extension;

                $produto->imagems()->save($imagemModel);
            }
        }

        Auth::user()->vendedor->produtos()->save($produto);

        return back()->with(['status' => 'Produto editado com sucesso.']);
    }
}

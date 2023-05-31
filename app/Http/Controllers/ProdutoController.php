<?php

namespace App\Http\Controllers;

use App\Models\Imagem;
use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProdutoController extends Controller
{
    public const categorias = [
        "smartphones", "laptops",
        "fragrances","skincare",
        "groceries","home-decoration",
        "furniture","tops","womens-dresses",
        "womens-shoes","mens-shirts",
        "mens-shoes","mens-watches",
        "womens-watches","womens-bags",
        "womens-jewellery","sunglasses",
        "automotive","motorcycle",
        "lighting"
    ];

    public function produto(Request $request)
    {
        $produto = [];

        if(!empty($request->id))
            $produto = Produto::where('id', $request->id)
                ->with('imagems')->first();
        
        return view('vendedor/produto', [
            'produto' => $produto, 
            'categorias' => self::categorias
        ]);
    }

    public function show(Request $request)
    {
        $produto = Produto::where('id', $request->id)
            ->with('imagems')->first();

        return view('comprador/produto', ['produto' => $produto]);
    }

    public function createProduto(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
            'category' => [
                'required',
                Rule::in(self::categorias)
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
        $request->imagesToRemove = json_decode($request->imagesToRemove[0]);

        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
            'category' => [
                'required',
                Rule::in(self::categorias)
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

        return redirect()->back();
    }
}

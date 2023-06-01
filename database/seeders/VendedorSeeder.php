<?php

namespace Database\Seeders;

use App\Models\Imagem;
use App\Models\Produto;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class VendedorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $response = Http::get('https://dummyjson.com/products?select=title,price,description,category,images');
        $responseArray = json_decode($response, true);

        $vendedor = \App\Models\Vendedor::factory()->create([
            'status' => 'A'
        ]);

        foreach ($responseArray['products'] as $produto) {
            $produtoNew = new Produto();

            $produtoNew['name'] = $produto['title'];
            $produtoNew['price'] = $produto['price'];
            $produtoNew['category'] = $produto['category'];
            $produtoNew['description'] = $produto['description'];

            $vendedor->produtos()->save($produtoNew);

            for ($i = 0; $i < count($produto['images']); $i++) {
                if($i == 3) break;

                $imagem = new Imagem();
                $imagem->path = $produto['images'][$i];

                $produtoNew->imagems()->save($imagem);
            }
        }
    }
}

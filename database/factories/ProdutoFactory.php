<?php

namespace Database\Factories;

use App\Models\Vendedor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Produto>
 */
class ProdutoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'vendedor_id' => Vendedor::factory()->create(['status' => 'A']),
            'name' => fake()->words(2, true),
            'description' => fake()->paragraph(),
            'price' => fake()->randomNumber(5, true),
            'category' => fake()->randomElement([
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
            ]),
            'visualization' => fake()->randomNumber(3)
        ];
    }
}

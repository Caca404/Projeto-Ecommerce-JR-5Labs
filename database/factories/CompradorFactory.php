<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comprador>
 */
class CompradorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {        
        return [
            'user_id' => User::factory()->create(['type' => 'comprador']),
            'cpf' => fake('PT_BR')->cpf(false),
            'birth_date' => fake()->dateTimeBetween('-40 years', '-20 years'),
            'state' => fake()->randomElement([
                "AC", "AL", "AP", "AM", "BA",
                "CE", "DF", "ES", "GO", "MA",
                "MT", "MS", "MG", "PA", "PB",
                "PR", "PE", "PI", "RJ", "RN",
                "RS", "RO", "RR", "SC", "SP",
                "SE", "TO"
            ]),
            'city' => fake()->randomElement([
                "Rio Branco","Maceió","Macapá",
                "Manaus","Salvador","Fortaleza",
                "Vitória","Goiânia","São Luís",
                "Cuiabá","Campo Grande","Belo Horizonte",
                "Belém", "João Pessoa","Curitiba",
                "Recife","Teresina","Rio de Janeiro",
                "Natal", "Porto Alegre", "Porto Velho",
                "Boa Vista", "Florianópolis", "São Paulo", 
                "Aracaju", "Palmas", "Brasília"
            ]), 
            'credits' => fake()->randomNumber(5, true),
        ];
    }
}

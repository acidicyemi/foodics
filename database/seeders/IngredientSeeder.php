<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use Illuminate\Database\Seeder;

class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ingredients = [
            ["name" => "Beef", "current_weight" => 20 * 1000, "initial_weight" => 20 * 1000],
            ["name" => "Cheese", "current_weight" => 5 * 1000, "initial_weight" => 5 * 1000],
            ["name" => "Onion", "current_weight" => 1 * 1000, "initial_weight" => 1 * 1000]
        ];

        foreach ($ingredients as $ingredient) {
            $i = Ingredient::firstOrNew(["name" => $ingredient["name"]]);
            $i->current_weight_in_grams = $ingredient["current_weight"];
            $i->initial_weight_in_grams = $ingredient["initial_weight"];
            $i->mail_sent = false;
            $i->last_refilled = now();
            $i->save();
        }
    }
}
